<?php
namespace console\controllers;

use Exception;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class CsvToDbController extends Controller
{
    public function actionImport($filePath, $tableName)
    {
        ini_set('memory_limit', '512M');

        $batchSize = 5000; // Tamaño del lote

        if (!file_exists($filePath)) {
            Console::output("El archivo no existe: $filePath");
            return;
        }

        // Abrir el archivo CSV
        if (($handle = fopen($filePath, 'r')) === false) {
            Console::output("No se pudo abrir el archivo: $filePath");
            return;
        }

        $db = Yii::$app->db; // Conexión MySQL
        $header = fgetcsv($handle); // Leer el encabezado
        if (!$header) {
            Console::output("No se pudo leer el encabezado del archivo.");
            fclose($handle);
            return;
        }

        // Elimina 'id' si existe en el encabezado (opcional, por seguridad)
        $header = array_filter($header, fn($col) => strtolower($col) !== 'id');

        $rows = [];
        $count = 0;

        while (($data = fgetcsv($handle)) !== false) {
            // Saltar líneas vacías
            if ($data === null || $data === false || count(array_filter($data, fn($v) => $v !== null && $v !== '')) === 0) {
                continue;
            }

            // Validar que la fila tenga el mismo número de columnas que el encabezado
            if (count($data) !== count($header)) {
                Console::output("Advertencia: Línea $count tiene un número incorrecto de columnas. Saltando...");
                Console::output("Header: " . json_encode($header));
                Console::output("Data: " . json_encode($data));
                $this->logProblematicRow($data);
                continue;
            }

            $rows[] = array_combine($header, $data);
            $count++;

            // Insertar en lotes
            if ($count % $batchSize === 0) {
                $this->batchInsertWithIgnore($db, $tableName, $header, $rows);
                $rows = []; // Limpiar lote
                gc_collect_cycles(); // Liberar memoria
                Console::output("Insertados $count registros...");
            }
        }

        // Insertar los registros restantes
        if (!empty($rows)) {
            $this->batchInsertWithIgnore($db, $tableName, $header, $rows);
            Console::output("Insertados $count registros en total.");
        }

        fclose($handle);
        Console::output("Proceso completado.");
    }

    private function batchInsertWithIgnore($db, $tableName, $columns, $rows)
    {
        $placeholders = [];
        $params = [];
        foreach ($rows as $i => $row) {
            foreach ($columns as $col) {
                // Verificar si el valor está vacío y asignar NULL si corresponde
                $row[$col] = ($row[$col] === "") ? null : $row[$col];
            }
            $placeholders[] = '(' . implode(',', array_map(fn($col) => ":{$col}_{$i}", $columns)) . ')';
            foreach ($columns as $col) {
                $params[":{$col}_{$i}"] = $row[$col];
            }
        }

        $columnsString = '`' . implode('`,`', $columns) . '`';
        $placeholdersString = implode(',', $placeholders);

        $sql = "INSERT IGNORE INTO {$tableName} ({$columnsString}) VALUES {$placeholdersString}";

        try {
            $db->createCommand($sql, $params)->execute();
        } catch (\Exception $e) {
            Console::output("Error al insertar lote: " . $e->getMessage());
            Yii::error($e->getMessage());
        }
    }

    private function logProblematicRow($data)
    {
        $logFile = 'frontend/web/uploads/postgres/problematic_rows.log';

        // Ensure the directory exists, create it if not
        $dir = dirname($logFile);  // Get the directory part of the path
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);  // Create the directory and any necessary parent directories
        }

        // Now write to the log file
        file_put_contents($logFile, implode(',', $data) . PHP_EOL, FILE_APPEND);
    }
}
<?php

namespace console\controllers;

use common\components\MyHelpers;
use common\models\u_combo_user\ComboTypes;
use common\models\u_combo_user\File;
use Yii;
use yii\console\Controller;

class UComboUploadController extends Controller
{
    public function actionIndex()
    {
        $combo_user_file = File::find()
            ->where([
                'or',
                ['status' => File::IS_PENDING],
                ['and', ['status' => File::IS_BEING_PROCESSED], ['<', 'updated_at', strtotime('-2 minutes')]],
            ])
            ->one();

        if (!$combo_user_file) {
            echo "No hay combos por subir" . PHP_EOL;
            return;
        }

        $filePath = $this->getFilePath($combo_user_file['file_name']);

        // Open the file for reading
        if (!$fileHandle = fopen($filePath, "r")) {
            echo "No se pudo abrir el archivo" . PHP_EOL;
            return;
        }

        $lastProcessedIdx = $combo_user_file['last_processed_idx'];
        $combo_user_file->status = File::IS_BEING_PROCESSED;
        $combo_type = $combo_user_file->getComboType();
        $slot_id = $combo_user_file->getComboSlotId();
        $u_combo_user_id = $combo_user_file->u_combo_user_id;

        $currentLine = 0;
        $batch = [];
        $batchSize = 4000;

        while (($line = fgets($fileHandle)) !== false) {
            if ($currentLine++ < $lastProcessedIdx) {
                continue;
            }



            // Prepare data based on combo type
            if ($combo_type == ComboTypes::$TYPE_EMAIL_PASS) {
                $preparedData = $this->prepareEmailPassData($u_combo_user_id, $slot_id, $line);
                if (is_array($preparedData)) {  // Only add if the result is a valid array
                    $batch[] = $preparedData;
                }
            } elseif ($combo_type == ComboTypes::$TYPE_USER_PASS) {
                $preparedData = $this->prepareUserPassData($u_combo_user_id, $slot_id, $line);
                if (is_array($preparedData)) {  // Only add if the result is a valid array
                    $batch[] = $preparedData;
                }
            } elseif ($combo_type == ComboTypes::$TYPE_NUMBER_PASS) {
                $preparedData = $this->prepareNumberPassData($u_combo_user_id, $slot_id, $line);
                if (is_array($preparedData)) {  // Only add if the result is a valid array
                    $batch[] = $preparedData;
                }
            } elseif ($combo_type == ComboTypes::$TYPE_EMAIL_PASS_PHONES) {
                $preparedData = $this->prepareEmailPassPhonesData($u_combo_user_id, $slot_id, $line);
                if (is_array($preparedData)) {  // Only add if the result is a valid array
                    $batch[] = $preparedData;
                }
            } elseif ($combo_type == ComboTypes::$TYPE_EMAIL_PHONES) {
                $preparedData = $this->prepareEmailPhonesData($u_combo_user_id, $slot_id, $line);
                if (is_array($preparedData)) {
                    $batch[] = $preparedData;
                }
            }

            

            // Process the batch once we reach 1000 entries
            if (count($batch) >= $batchSize) {
                echo "Procesando linea $currentLine " . PHP_EOL;
                // Update the last processed line index and save
                $combo_user_file->last_processed_idx = $currentLine;
                $combo_user_file->save();
                $this->processBatch($batch, 'u_combo_user_list');

                $batch = [];
            }
        }

        // echo var_dump($batch) . PHP_EOL;
        // return;
        // Process any remaining lines in the batch
        if (!empty($batch)) {
            $combo_user_file->last_processed_idx = $currentLine;
            $combo_user_file->save();
            $this->processBatch($batch, 'u_combo_user_list');
        }

        // Check if we've reached the end of the file
        if (feof($fileHandle)) {
            $combo_user_file->status = File::IS_FINISHED;
            $combo_user_file->save();
            echo "Combo fue finalizado...." . PHP_EOL;
        }

        // Close the file handle
        fclose($fileHandle);
    }

    private function getFilePath($fileName)
    {
        $uploadPath = Yii::getAlias('@frontend/web/uploads/combos');
        return $uploadPath . '/' . $fileName;
    }

    // Prepare data for batch insertion (for email-pass)
    private function prepareEmailPassData($combo_user_id, $slot_id, $line)
    {
        if (count(explode(':', $line)) < 2) return false;
        list($user, $pass) = explode(':', $line);

        if (!MyHelpers::validateEmail($user)) return false;

        return [
            'u_combo_user_id' => $combo_user_id,
            'u_combo_user_slot_id' => $slot_id,
            'full_string' => trim($user) . ':' . trim($pass),
            'status' => null,  // Default status or change it as needed
            'type' => ComboTypes::$TYPE_EMAIL_PASS,
            'phones' => null,
        ];
    }

    // Prepare data for batch insertion (for user-pass)
    private function prepareUserPassData($combo_user_id, $slot_id, $line)
    {
        if (count(explode(':', $line)) < 2) return false;
        list($user, $pass) = explode(':', $line);

        return [
            'u_combo_user_id' => $combo_user_id,
            'u_combo_user_slot_id' => $slot_id,
            'full_string' => trim($user) . ':' . trim($pass),
            'status' => null,  // Default status or change it as needed
            'type' => ComboTypes::$TYPE_USER_PASS,
            'phones' => null,
        ];
    }

    // Prepare data for batch insertion (for number-pass)
    private function prepareNumberPassData($combo_user_id, $slot_id, $line)
    {
        if (count(explode(':', $line)) < 2) return false;
        list($user, $pass) = explode(':', $line);

        if (!MyHelpers::hasAtLeastTenDigits($user)) return false;

        return [
            'u_combo_user_id' => $combo_user_id,
            'u_combo_user_slot_id' => $slot_id,
            'full_string' => MyHelpers::normalizePhoneNumber(trim($user)) . ':' . trim($pass),
            'status' => null,  // Default status or change it as needed
            'type' => ComboTypes::$TYPE_NUMBER_PASS,
            'phones' => null,
        ];
    }

    private function prepareEmailPassPhonesData($combo_user_id, $slot_id, $line)
    {
        if (count(explode(':', $line)) < 3) return false;
        list($user, $pass, $phones) = explode(':', $line);

        if (!MyHelpers::validateEmail($user)) return false;

        return [
            'u_combo_user_id' => $combo_user_id,
            'u_combo_user_slot_id' => $slot_id,
            'full_string' => trim($user) . ':' . trim($pass),
            'status' => null,  // Default status or change it as needed
            'type' => ComboTypes::$TYPE_EMAIL_PASS_PHONES,
            'phones' => trim($phones),
        ];
    }

    private function prepareEmailPhonesData($combo_user_id, $slot_id, $line)
    {
        if (count(explode(':', $line)) < 2) return false;
        list($user, $phones) = explode(':', $line);

        if (!MyHelpers::validateEmail($user)) return false;

        if (!MyHelpers::hasAtLeastTenDigits($phones)) return false;

        return [
            'u_combo_user_id' => $combo_user_id,
            'u_combo_user_slot_id' => $slot_id,
            'full_string' => trim($user) . ':' . MyHelpers::normalizePhoneNumber(trim($phones)),
            'status' => null,  // Default status or change it as needed
            'type' => ComboTypes::$TYPE_EMAIL_PHONES,
            'phones' => MyHelpers::normalizePhoneNumber(trim($phones)),
        ];
    }

    // Batch insertion using `INSERT IGNORE` to ignore duplicates
    private function processBatch($batch, $tableName)
    {
        if (empty($batch)) {
            return;
        }

        // Insert rows into the database and ignore duplicates
        $columns = ['u_combo_user_id', 'u_combo_user_slot_id', 'full_string', 'status', 'type', 'phones'];

        $db = Yii::$app->db;

        if (isset(Yii::$app->pgsql)) {
            $db = Yii::$app->pgsql;
        }

        $sql = $db->queryBuilder->batchInsert($tableName, $columns, $batch);


        // For MySQL, replace `INSERT INTO` with `INSERT IGNORE INTO`
        if ($db->driverName === 'mysql') {
            $sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
        }

        // For PostgreSQL, handle conflicts by adding ON CONFLICT DO NOTHING
        if ($db->driverName === 'pgsql') {
            // Modify the SQL for PostgreSQL conflict handling
            $sql .= ' ON CONFLICT DO NOTHING'; // No conflict target specified
        }

        try {
            $db->createCommand($sql)->execute();
            echo "Inserted" . PHP_EOL;
        } catch (\Exception $e) {
            // Log the error and continue
            Yii::error("Batch insert error: " . $e->getMessage());
            echo $e;
        }
    }
}

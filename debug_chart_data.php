<?php
/**
 * Debug Chart Data
 * 
 * Este script verifica que los datos del gráfico se estén generando correctamente
 */

require_once 'vendor/autoload.php';
require_once 'common/config/bootstrap.php';
require_once 'frontend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require 'common/config/main.php',
    require 'common/config/main-local.php',
    require 'frontend/config/main.php',
    require 'frontend/config/main-local.php'
);

$app = new yii\web\Application($config);

echo "Debug Chart Data\n";
echo "================\n\n";

try {
    // Verificar eventos en la base de datos
    $totalEvents = common\models\Event::find()->count();
    echo "📊 Total eventos en DB: $totalEvents\n\n";
    
    if ($totalEvents === 0) {
        echo "❌ No hay eventos en la base de datos.\n";
        echo "🔧 Genera datos con: /event-feeder/index o haz clic en 'Quick Data'\n";
        exit;
    }
    
    // Simular el método getChartData
    $fromDate = date('Y-m-d', strtotime('-7 days'));
    $toDate = date('Y-m-d');
    
    echo "📅 Rango de fechas: $fromDate a $toDate\n\n";
    
    // Query de eventos diarios
    $dailyEvents = common\models\Event::find()
        ->select(['DATE(ts) as date', 'COUNT(*) as total', 'type'])
        ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
        ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
        ->groupBy(['DATE(ts)', 'type'])
        ->asArray()
        ->all();
    
    echo "📈 Eventos encontrados por día/tipo: " . count($dailyEvents) . "\n";
    
    if (count($dailyEvents) === 0) {
        echo "❌ No hay eventos en el rango de fechas seleccionado.\n";
        echo "🔧 Los eventos deben estar dentro de los últimos 7 días.\n";
        exit;
    }
    
    echo "\n📋 Datos crudos de la consulta:\n";
    echo "================================\n";
    foreach ($dailyEvents as $event) {
        echo "Fecha: {$event['date']}, Tipo: {$event['type']}, Total: {$event['total']}\n";
    }
    
    // Generar estructura de datos como el controlador
    $trendData = [];
    $dates = [];
    
    $current = strtotime($fromDate);
    $end = strtotime($toDate);
    while ($current <= $end) {
        $date = date('Y-m-d', $current);
        $dates[] = $date;
        $trendData[$date] = [
            'date' => $date,
            'page_views' => 0,
            'cta_clicks' => 0,
            'total' => 0,
        ];
        $current = strtotime('+1 day', $current);
    }
    
    // Llenar con datos reales
    foreach ($dailyEvents as $event) {
        $date = $event['date'];
        if (isset($trendData[$date])) {
            if ($event['type'] === 'page_view') {
                $trendData[$date]['page_views'] = (int)$event['total'];
            } elseif ($event['type'] === 'cta_click') {
                $trendData[$date]['cta_clicks'] = (int)$event['total'];
            }
            $trendData[$date]['total'] += (int)$event['total'];
        }
    }
    
    echo "\n📊 Datos procesados para el gráfico:\n";
    echo "====================================\n";
    foreach ($trendData as $data) {
        echo "Fecha: {$data['date']}\n";
        echo "  - Page Views: {$data['page_views']}\n";
        echo "  - CTA Clicks: {$data['cta_clicks']}\n";
        echo "  - Total: {$data['total']}\n";
        echo "\n";
    }
    
    // Verificar que hay datos con valores > 0
    $hasData = false;
    foreach ($trendData as $data) {
        if ($data['total'] > 0) {
            $hasData = true;
            break;
        }
    }
    
    if ($hasData) {
        echo "✅ El gráfico debería mostrar datos correctamente!\n";
        echo "📈 JSON para el gráfico:\n";
        echo json_encode(array_values($trendData), JSON_PRETTY_PRINT);
    } else {
        echo "❌ No hay datos con valores > 0 en el rango seleccionado.\n";
        echo "🔧 Genera eventos para estos días o ajusta el rango de fechas.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📋 Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n";
?>


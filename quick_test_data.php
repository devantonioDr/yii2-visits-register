<?php
/**
 * Quick Test Data Generator
 * 
 * Generates a small amount of test data to see the Events Trend chart in action
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

echo "Quick Test Data Generator\n";
echo "========================\n\n";

// Sample data
$pages = [
    'https://example.com/home',
    'https://example.com/products',
    'https://example.com/about',
    'https://example.com/contact',
    'https://example.com/blog',
];

$ctas = [
    'buy-now-button',
    'signup-form',
    'contact-us',
    'learn-more',
];

$devices = ['desktop', 'mobile', 'tablet'];

// Generate 50 events over the last 7 days
$count = 50;
$days = 7;
$startDate = strtotime("-{$days} days");
$endDate = time();

echo "Generating $count events over the last $days days...\n";

$generated = 0;
$visitIds = [];

// Generate some visit IDs
for ($i = 0; $i < 10; $i++) {
    $visitIds[] = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

for ($i = 0; $i < $count; $i++) {
    $event = new common\models\Event();
    
    // Random time within the date range
    $randomTime = mt_rand($startDate, $endDate);
    $event->ts = date('Y-m-d H:i:s', $randomTime);
    
    // 70% page views, 30% CTA clicks
    $event->type = mt_rand(1, 10) <= 7 ? 'page_view' : 'cta_click';
    $event->page = $pages[array_rand($pages)];
    
    if ($event->type === 'cta_click') {
        $event->cta_id = $ctas[array_rand($ctas)];
    }
    
    $event->visit_id = $visitIds[array_rand($visitIds)];
    $event->device = $devices[array_rand($devices)];
    
    // Generate hashes
    $event->ip_hash = hash('sha256', '192.168.1.' . mt_rand(1, 255) . Yii::$app->params['eventSalt']);
    $event->ua_hash = hash('sha256', 'Mozilla/5.0 test user agent' . mt_rand(1, 1000) . Yii::$app->params['eventSalt']);
    
    // Add meta data
    $meta = [
        'screen_resolution' => mt_rand(0, 1) ? '1920x1080' : '1366x768',
        'timezone' => 'America/New_York',
        'utm_source' => ['google', 'facebook', 'direct'][array_rand([0, 1, 2])],
        'utm_medium' => ['cpc', 'organic', 'social'][array_rand([0, 1, 2])],
    ];
    
    $event->setMetaArray($meta);
    
    if ($event->save()) {
        $generated++;
    }
}

echo "\n";
echo "✅ Test data generation completed!\n";
echo "📊 Generated: $generated events\n";
echo "📅 Date range: " . date('Y-m-d', $startDate) . " to " . date('Y-m-d', $endDate) . "\n";
echo "🎯 Event types: page_view and cta_click\n";
echo "📱 Devices: desktop, mobile, tablet\n";
echo "\n";
echo "🚀 Now you can view the Analytics Dashboard at:\n";
echo "   /analytics-dashboard/index\n";
echo "\n";
echo "The Events Trend chart should now show data! 📈\n";
?>

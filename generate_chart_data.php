<?php
/**
 * Generate Chart Data Script
 * 
 * Creates sample events to test the Events Trend chart
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

echo "Generating Chart Data\n";
echo "====================\n\n";

// Clear existing data first
echo "Clearing existing events...\n";
$deleted = common\models\Event::deleteAll();
echo "Deleted $deleted existing events.\n\n";

// Generate events for the last 7 days
$days = 7;
$eventsPerDay = 15; // 15 events per day for good visualization

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

// Generate visit IDs
$visitIds = [];
for ($i = 0; $i < 8; $i++) {
    $visitIds[] = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

$totalGenerated = 0;

// Generate events for each day
for ($day = 0; $day < $days; $day++) {
    $date = date('Y-m-d', strtotime("-$day days"));
    echo "Generating events for $date...\n";
    
    for ($i = 0; $i < $eventsPerDay; $i++) {
        $event = new common\models\Event();
        
        // Random time during the day
        $hour = mt_rand(8, 20); // Business hours
        $minute = mt_rand(0, 59);
        $second = mt_rand(0, 59);
        
        $event->ts = date('Y-m-d H:i:s', mktime($hour, $minute, $second, date('m', strtotime($date)), date('d', strtotime($date)), date('Y', strtotime($date))));
        
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
            $totalGenerated++;
        }
    }
}

echo "\n";
echo "✅ Chart data generation completed!\n";
echo "📊 Total events generated: $totalGenerated\n";
echo "📅 Date range: " . date('Y-m-d', strtotime("-$days days")) . " to " . date('Y-m-d') . "\n";
echo "📈 Events per day: $eventsPerDay\n";
echo "\n";

// Verify the data
echo "🔍 Verifying generated data:\n";
$totalEvents = common\models\Event::find()->count();
$pageViews = common\models\Event::find()->where(['type' => 'page_view'])->count();
$ctaClicks = common\models\Event::find()->where(['type' => 'cta_click'])->count();
$uniqueVisits = common\models\Event::find()->select('visit_id')->distinct()->count();

echo "   Total Events: $totalEvents\n";
echo "   Page Views: $pageViews\n";
echo "   CTA Clicks: $ctaClicks\n";
echo "   Unique Visits: $uniqueVisits\n";

// Test the chart query
echo "\n📈 Testing chart data query:\n";
$fromDate = date('Y-m-d', strtotime("-$days days"));
$toDate = date('Y-m-d');

$dailyEvents = common\models\Event::find()
    ->select(['DATE(ts) as date', 'COUNT(*) as total', 'type'])
    ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
    ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
    ->groupBy(['DATE(ts)', 'type'])
    ->asArray()
    ->all();

echo "   Daily events found: " . count($dailyEvents) . "\n";
echo "   Sample data:\n";
foreach (array_slice($dailyEvents, 0, 5) as $event) {
    echo "   - " . $event['date'] . ": " . $event['type'] . " = " . $event['total'] . "\n";
}

echo "\n🚀 Now visit /analytics-dashboard/index to see the Events Trend chart with data!\n";
echo "📊 The chart should show a line graph with Page Views and CTA Clicks over time.\n";
?>

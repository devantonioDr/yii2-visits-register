<?php
/**
 * Test Database Connection and Event Table
 * 
 * This script tests if the database connection works and the event table exists
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

echo "Database Connection Test\n";
echo "=======================\n\n";

try {
    // Test database connection
    $connection = Yii::$app->db;
    echo "✅ Database connection: OK\n";
    
    // Test if Event table exists
    $tableExists = $connection->createCommand("SHOW TABLES LIKE 'event'")->queryOne();
    if ($tableExists) {
        echo "✅ Event table exists: OK\n";
        
        // Get table structure
        $columns = $connection->createCommand("DESCRIBE event")->queryAll();
        echo "📋 Table structure:\n";
        foreach ($columns as $column) {
            echo "   - {$column['Field']}: {$column['Type']}\n";
        }
        
        // Count existing records
        $count = $connection->createCommand("SELECT COUNT(*) FROM event")->queryScalar();
        echo "\n📊 Current records: $count\n";
        
        if ($count > 0) {
            // Show sample data
            $sample = $connection->createCommand("SELECT * FROM event ORDER BY ts DESC LIMIT 3")->queryAll();
            echo "\n📝 Sample records:\n";
            foreach ($sample as $record) {
                echo "   - ID: {$record['id']}, Type: {$record['type']}, Page: {$record['page']}, TS: {$record['ts']}\n";
            }
        }
        
    } else {
        echo "❌ Event table does not exist\n";
        echo "🔧 Run migrations: php yii migrate\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "🔧 Check your database configuration in common/config/main-local.php\n";
}

echo "\n";
?>

<?php

// Debug file to test application loading

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing Yii2 Application Loading...\n";

try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "✅ Autoloader loaded successfully\n";
    
    require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
    echo "✅ Yii2 core loaded successfully\n";
    
    $config = require __DIR__ . '/../config/main.php';
    echo "✅ Configuration loaded successfully\n";
    
    $app = new yii\web\Application($config);
    echo "✅ Application created successfully\n";
    
    echo "✅ All tests passed! Application should work.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 
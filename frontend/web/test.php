<?php

// Test file to check database connection and model loading

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../config/main.php',
    require __DIR__ . '/../../config/main-local.php'
);

unset($config['components']['errorHandler']);
unset($config['components']['user']);
unset($config['components']['session']);

$app = new yii\console\Application($config);

try {
    echo "X Testing database connection...\n";
    $app->db->open();
    echo "Database connection successful!\n";
    
    echo "Testing User model...\n";
    $user = new \common\models\User();
    echo "User model loaded successfully!\n";
    
    echo "Testing Issue model...\n";
    $issue = new \common\models\Issue();
    echo "Issue model loaded successfully!\n";
    
    echo "All tests passed!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 
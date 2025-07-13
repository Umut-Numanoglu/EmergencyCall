<?php

// Test file to check database connection and model loading

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=emergency_call',
            'username' => 'emergency_user',
            'password' => 'emergency_password',
            'charset' => 'utf8',
        ],
    ],
];

$app = new yii\console\Application($config);

try {
    echo "Testing database connection...\n";
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
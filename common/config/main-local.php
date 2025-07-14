<?php
return [
    'components' => [
        'request' => [
            'cookieValidationKey' => '1362008314fbd6f0beeccc4c9c72aab2548f3f456cffb8563c729430097d9aa8',
        ],
        'db' => [
            'class' => 'yii\\db\\Connection',
            'dsn' => 'mysql:host=db;dbname=emergency_call',
            'username' => 'emergency_user',
            'password' => 'emergency_password',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\\swiftmailer\\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
    ],
]; 
<?php
$params = array_merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

// Set the alias
Yii::setAlias('@frontend', dirname(__DIR__) . '/frontend');
Yii::setAlias('@webroot', dirname(__DIR__) . '/web');
Yii::setAlias('@web', '/');
Yii::setAlias('@vendor', dirname(__DIR__) . '/vendor');

return [
    'id' => 'emergency-call-frontend',
    'name' => 'Emergency Call System (Frontend)',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerNamespace' => 'frontend\\controllers',
    'viewPath' => dirname(__DIR__) . '/views',
    'layoutPath' => dirname(__DIR__) . '/views/layouts',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\\models\\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'login' => 'site/login',
                'register' => 'site/register',
                'logout' => 'site/logout',
                'issues' => 'issue/index',
                'issues/create' => 'issue/create',
                'issues/<id:\d+>' => 'issue/view',
                'admin' => 'admin/index',
                'admin/issues' => 'admin/index',
                'doctor' => 'doctor/index',
                'doctor/issues' => 'doctor/index',
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=emergency_call',
            'username' => 'emergency_user',
            'password' => 'emergency_password',
            'charset' => 'utf8',
        ],
    ],
    'params' => $params,
]; 
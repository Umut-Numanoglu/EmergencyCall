<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php'
);

return [
    'id' => 'emergency-call-frontend',
    'name' => 'Emergency Call System (Frontend)',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'controllerNamespace' => 'frontend\\controllers',
    'viewPath' => dirname(__DIR__) . '/views',
    'layoutPath' => dirname(__DIR__) . '/views/layouts',
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\\i18n\\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ]
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => '1362008314fbd6f0beeccc4c9c72aab2548f3f456cffb8563c729430097d9aa8',
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
                    'class' => 'yii\\log\\FileTarget',
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
            'class' => 'yii\\db\\Connection',
            'dsn' => 'mysql:host=db;dbname=emergency_call',
            'username' => 'emergency_user',
            'password' => 'emergency_password',
            'charset' => 'utf8',
        ],
    ],
    'params' => $params,
]; 
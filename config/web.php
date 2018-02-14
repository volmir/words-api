<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'word-combination',
    'basePath' => dirname(__DIR__),
    'name' => 'Составь слова',
    'language' => 'ru-RU',
    'version' => '0.9.0',
    'bootstrap' => ['log'],
    'defaultRoute' => 'word/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'asdl2na672kwm3wmw',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
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
        'fileCache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'memCache' => [
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true,
            'servers' => [
                [
                    'host' => '127.0.0.1',
                    'port' => 11211,
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mx1.mirohost.net',
                'username' => 'noreply@combination.cf',
                'password' => 'DQsEvHonwhSs',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                //'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',

                'about' => 'word/about',
                'rules' => 'word/rules',
                'answers' => 'word/answers',
                'description' => 'word/description',
                'game' => 'word/game',
                'game/finish' => 'word/finish',
                'game/help' => 'word/help',
                'contacts' => 'site/contact',
                
                'GET words/<word>' => 'api/default/words',
                'GET description/<word>' => 'api/default/description',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
//    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//        // uncomment the following to add your IP if you are not connecting from localhost.
//        //'allowedIPs' => ['127.0.0.1', '::1'],
//    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

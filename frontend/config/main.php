<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'Motivation service',
    'language' => 'ru-RU', // язык приложения
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
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
        'urlManager' => [
            'baseUrl' => '',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //[
                //    'pattern' => '<action:(about|contact|login|signup)>',
                //    'route' => 'site/<action>',
                //    'suffix' => '.html'
                //],
                '<action:(about|contact|login|signup)>' => 'site/<action>',
                'motivation/views/<id:\d+>' => 'motivation/views',
                'motivation/control/<id:\d+>' => 'motivation/control',
                
                'motivation/edit/<id:\d+>/task' => 'task/index',
                'motivation/edit/<id:\d+>/task/create' => 'task/create',
                'motivation/views/<motivation_id:\d+>/editactivitytask/<task_id:\d+>' => 'motivation/editactivitytask',
                'motivation/views/<motivation_id:\d+>/editactivityreward/<reward_id:\d+>' => 'motivation/editactivityreward',
                
                'motivation/edit/<motivation_id:\d+>/level' => 'motivation-level/index',
                'motivation/edit/<motivation_id:\d+>/level/create' => 'motivation-level/create',
                
                'motivation/edit/<motivation_id:\d+>/reward' => 'reward/index',
                'motivation/edit/<motivation_id:\d+>/reward/create' => 'reward/create',
            ],
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer'
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-purple',
                ],
            ],
        ],
    ],
    'params' => $params,
];

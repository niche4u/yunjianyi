<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => '云建议',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'aliases' => [
        '@views' => dirname(__DIR__) . "/views/",
        '@jsUrl' => Yii::$app->request->baseUrl."/js/"
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
            //'baseUrl' => '//cdn.yunjianyi.com/assets', //使用cdn
            'baseUrl' => '/assets',
            'bundles' => [
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => array(
                '<alias:recent|search>' => 'site/<alias>',
                '<alias:setting|login|logout|signup>' => 'account/<alias>',
                'create' => 'topic/create',
                'create/<node:\w+>' => 'topic/create',
                'node/<nodeName:\w+>' => 'node/view',
                'topic/<id:\d+>' => 'topic/view',
                'topic/<action:\w+>/<id:\d+>' => 'topic/<action>',
                'follow/<action:\w+>/<type:\d+>/<follow:\d+>' => 'follow/<action>',
                'member/<username:\w+>' => 'member/index',
                'member/<username:\w+>/topic' => 'member/topic',
                'member/<username:\w+>/reply' => 'member/reply',

                'rss.xml' => 'site/rss',
            ),
        ],
	    'xunsearch' => [
            'class' => 'hightman\xunsearch\Connection', // 此行必须
            'iniDirectory' => '@app/config',    // 搜索 ini 文件目录，默认：@vendor/hightman/xunsearch/app
            'charset' => 'utf-8',   // 指定项目使用的默认编码，默认即时 utf-8，可不指定
        ],

        // 'authClientCollection' => [
        //     'class' => 'yii\authclient\Collection',
        //     'clients' => [
        //         'qq' => [
        //             'class' => 'xj\oauth\QqAuth',
        //             'clientId' => '101222501',
        //             'clientSecret' => '61a94f74aee16b274bff371c1a72edd3'

        //         ],
        //         'sina' => [
        //             'class' => 'xj\oauth\WeiboAuth',
        //             'clientId' => '111',
        //             'clientSecret' => '111',
        //         ],
        //         'weixin' => [
        //             'class' => 'xj\oauth\WeixinAuth',
        //             'clientId' => '111',
        //             'clientSecret' => '111',
        //         ],
        //     ]
        // ],
    ],
    'params' => $params,
];

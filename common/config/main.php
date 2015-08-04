<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'aliases' => [
        '@avatar' => dirname(__DIR__) . "/../frontend/web/uploads/avatar",
        '@node' => dirname(__DIR__) . "/../frontend/web/uploads/node",
        '@tab' => dirname(__DIR__) . "/../frontend/web/uploads/tab",
        '@bg' => dirname(__DIR__) . "/../frontend/web/uploads/bg",
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@frontend/runtime/cache'
        ],
//        'cache' => [
//            'class' => 'yii\caching\MemCache',
//            'useMemcached' => true,
//            'servers' => [
//                [
//                    'host' => 'localhost',
//                    'port' => 11211,
//                ],
//            ],
//        ],

        'formatter' => [
            'defaultTimeZone' => 'UTC',
            'timeZone' => 'Asia/Shanghai',
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat'=>'php:Y-m-d H:i:s'
        ],
    ],
];

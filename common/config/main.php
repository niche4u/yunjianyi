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
    ],
];

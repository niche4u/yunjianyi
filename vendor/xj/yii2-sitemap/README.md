yii2-sitemap
============

composer.json
-----
```json
"require": {
    "xj/yii2-sitemap": "*"
},
```

ActiveRecord DATASOURCE
---
```php
//AR DATASOURCE
public function actions() {
    return [
        //FOR AR DATA
        'sitemap-ar-index' => [
            'class' => SitemapIndexAction::className(),
            'route' => ['sitemap-ar'],
            'dataProvider' => new ActiveDataProvider([
                'query' => \app\models\User::find(),
                'pagination' => [
                    'pageParam' => 'p',
                    'pageSize' => 1,
                ]]),
        ],
        'sitemap-ar' => [
            'class' => SitemapUrlsetAction::className(),
            'gzip' => YII_DEBUG ? false : true,
            'dataProvider' => new ActiveDataProvider([
                'query' => \app\models\User::find(),
                'pagination' => [
                    'pageParam' => 'p',
                    'pageSize' => 1,
                ]]),
            'remap' => [
                'loc' => function($model) {
                    return $model->username;
                },
                'lastmod' => function($model) {
                    return date(DATE_W3C);
                },
                'changefreq' => function($model) {
                    return Url::CHANGEFREQ_NEVER;
                },
                'priority' => function($model) {
                    return '1.0';
                },
            ],
        ],
    ];
}
```
Array DATASOURCE
---
```php
public function actions() {
    return [
        //FOR DIRECT DATA
        'sitemap-direct-index' => [
            'class' => SitemapIndexAction::className(),
            'route' => ['sitemap-direct'],
            'dataProvider' => new ArrayDataProvider([
                'allModels' => [
                    Sitemap::create(\yii\helpers\Url::to(['sitemap-direct', 'p' => 1], true), date(DATE_W3C)),
                    Sitemap::create(\yii\helpers\Url::to(['sitemap-direct', 'p' => 2], true), date(DATE_W3C)),
                    Sitemap::create(\yii\helpers\Url::to(['sitemap-direct', 'p' => 3], true), date(DATE_W3C)),
                ],
                'pagination' => [
                    'pageParam' => 'p',
                    'pageSize' => 1,
                ]
                    ]),
        ],
        'sitemap-direct' => [
            'class' => SitemapUrlsetAction::className(),
            'gzip' => YII_DEBUG ? false : true,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => [
                    Sitemap::create('http://url-a', date(DATE_W3C)),
                    Sitemap::create('http://url-b', date(DATE_W3C)),
                    Sitemap::create('http://url-c', date(DATE_W3C)),
                    Sitemap::create('http://url-d', date(DATE_W3C)),
                    Sitemap::create('http://url-e', date(DATE_W3C)),
                    Sitemap::create('http://url-f', date(DATE_W3C)),
                ],
                'pagination' => [
                    'pageParam' => 'p',
                    'pageSize' => 2,
                ]
                    ]),
            'remap' => function($model) {
                /* @var $model Sitemap */
                return Url::create($model->loc, $model->lastmod, 1, Url::CHANGEFREQ_NEVER);
            },
        ],
    ];
}
```

UrlManager
---
```php
[
    'class' => 'yii\web\UrlManager',
    'showScriptName' => false,
    'enablePrettyUrl' => true,
    'rules' => [
        'sitemap.xml' => 'sitemap/sitemap-ar-index',
        'sitemap.<p:\d+>.xml.gz' => 'sitemap/sitemap-ar',
    ],
];
```

Access
---
```
http://domain/sitemap.xml
http://domain/sitemap.1.xml.gz
```
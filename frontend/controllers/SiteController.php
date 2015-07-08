<?php
namespace frontend\controllers;

use common\models\Node;
use common\models\Page;
use common\models\Search;
use common\models\Tab;
use common\models\Topic;
use xj\sitemap\actions\SitemapUrlsetAction;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $title = '';
    public $description = '';
    public $keyword = '';
    public $bg = null;
    public $bg_color = null;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
            'rss' => [
                'class' => SitemapUrlsetAction::className(),
                'dataProvider' => new ActiveDataProvider([
                    'query' => Topic::find()->orderBy('updated_at DESC'),
                    'pagination' => [
                        'pageParam' => 'p',
                        'pageSize' => 100,
                    ]]),
                'remap' => [
                    'loc' => function($model) {
                        return Url::to('/topic/'.$model->id, true);
                    },
                    'lastmod' => function($model) {
                        return date(DATE_W3C, $model->updated_at);
                    },
                    'changefreq' => function($model) {
                        return 'daily';
                    },
                    'priority' => function($model) {
                        return '0.8';
                    },
                ],
            ],
        ];
    }

    /**
     * Success Callback
     * @param QqAuth|WeiboAuth $client
     * @see http://wiki.connect.qq.com/get_user_info
     * @see http://stuff.cebe.cc/yii2docs/yii-authclient-authaction.html
     */
    public function successCallback($client) {
        $id = $client->getId(); // qq | sina | weixin
        $attributes = $client->getUserAttributes(); // basic info
        $userInfo = $client->getUserInfo(); // user extend info
        var_dump($id, $attributes, $userInfo);
    }

    public function actionIndex()
    {
        $this->title = Yii::$app->name;
        $this->description = 'V2SEX，一个技术宅的专属社区。';

        $tab = Yii::$app->request->get('tab');
        $sessionTab = Yii::$app->session->get('tab');
        if(Yii::$app->request->get('tab') === null) {
            if(empty($sessionTab)) {
                Yii::$app->session->set('tab', 'tech');
                $tab = 'tech';
            }
            else {
                $tab = $sessionTab;
            }
        }
        else {
            if(Tab::findOne(['enname' => $tab]) !== null) {
                Yii::$app->session->set('tab', $tab);
            }
            else {
                Yii::$app->session->set('tab', 'tech');
                $tab = 'tech';
            }
        }

        $tabModel = Tab::find()->where(['enname' => $tab])->one();
        if(!empty($tabModel->bg) && $tabModel->use_bg == 1) $this->bg = $tabModel->bg;
        if(!empty($tabModel->bg_color)) $this->bg_color = $tabModel->bg_color;

        if($tab == 'new') $topic = Topic::find()->orderBy(['updated_at' => SORT_DESC])->limit(20)->all();
        else $topic = Topic::find()->where(['in', 'node_id', Tab::SubNodeId($tab)])->orderBy(['updated_at' => SORT_DESC])->limit(20)->all();
        return $this->render('index', ['topic' => $topic]);
    }

    public function actionRecent()
    {
        $this->title = '最近的主题 - '.Yii::$app->name;
        $this->description = '';

        $query = Topic::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $topic = $query->orderBy(['id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('recent', ['topic' => $topic, 'pagination'=> $pagination]);
    }

    public function actionSearch()
    {
        $keyword = Yii::$app->request->get('keyword');
        $page = Yii::$app->request->get('page', 1);
        if($page < 1) $page = 1;
        if(empty($keyword)) $this->goHome();

        $this->title = $keyword.' - 搜索 - '.Yii::$app->name;
        $this->description = '';
        $this->keyword = $keyword;

        $search = \Yii::$app->xunsearch->getDatabase('search')->getSearch();
        $search->setFuzzy();
        $search->setQuery($keyword);
        $search->setLimit(Yii::$app->params['pageSize'], ($page - 1) * Yii::$app->params['pageSize']);
        $topic = $search->search();

        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => count($topic)
        ]);

        return $this->render('search', ['topic' => $topic, 'pagination'=> $pagination, 'keyword'=> $keyword]);
    }

    public function actionAbout()
    {
        $this->title = '关于 - '.Yii::$app->name;
        $this->description = '';

        $model = Page::findOne(['route' => 'about']);
        return $this->render('page', ['model' => $model]);
    }

}

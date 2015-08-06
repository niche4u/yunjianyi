<?php
namespace frontend\controllers;

use common\models\Node;
use common\models\Tab;
use common\models\Topic;
use common\models\User;
use xj\sitemap\actions\SitemapUrlsetAction;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends FrontendController
{
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
        $this->description = '云建议，一个收集建议的地方。';

        $tab = Yii::$app->request->get('tab');
        $sessionTab = Yii::$app->session->get('tab');
        if(Yii::$app->request->get('tab') === null) {
            if(empty($sessionTab)) {
                Yii::$app->session->set('tab', 'new');
                $tab = 'new';
            }
            else {
                $tab = $sessionTab;
            }
        }
        else {
            if(Tab::Info($tab) !== null) {
                Yii::$app->session->set('tab', $tab);
            }
            else {
                Yii::$app->session->set('tab', 'new');
                $tab = 'new';
            }
        }

        $tabModel = Tab::Info($tab);
        if(!empty($tabModel['bg']) && $tabModel['use_bg'] == 1) $this->bg = $tabModel['bg'];
        if(!empty($tabModel['bg_color'])) $this->bg_color = $tabModel['bg_color'];
        $this->canonical = Yii::$app->params['domain'].'?tab='.$tab;

        if($tab == 'new') {
            $topic = (new Query())
                ->select('topic.*, node.enname, node.name, user.username, user.avatar')
                ->from(Topic::tableName().' topic')
                ->leftJoin(Node::tableName().' node', 'node.id = topic.node_id')
                ->leftJoin(User::tableName().' user', 'user.id = topic.user_id')
                ->where(['node.is_hidden' => 0])
                ->orderBy(['topic.updated_at' => SORT_DESC])
                ->limit(Yii::$app->params['pageSize'])
                ->all();
        }else {
            $topic = (new Query())
                ->select('topic.*, node.enname, node.name, user.username, user.avatar')
                ->from(Topic::tableName())
                ->leftJoin(Node::tableName(), 'node.id = topic.node_id')
                ->leftJoin(User::tableName(), 'user.id = topic.user_id')
                ->where(['in', 'topic.node_id', Tab::SubNodeId($tab)])
                ->orderBy(['topic.updated_at' => SORT_DESC])
                ->limit(Yii::$app->params['pageSize'])
                ->all();
        }
        return $this->render('index', ['topic' => $topic]);
    }

    public function actionRecent()
    {
        $this->title = '最近的建议 - '.Yii::$app->name;
        $this->description = '';
        $this->canonical = Yii::$app->params['domain'].'recent';

        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => (new Query())->from(Topic::tableName().' topic')->count()
        ]);
        $topic = (new Query())->select('topic.*, node.enname, node.name, user.username, user.avatar')->from(Topic::tableName().' topic')->leftJoin(Node::tableName().' node', 'node.id = topic.node_id')->leftJoin(User::tableName().' user', 'user.id = topic.user_id')->where(['node.is_hidden' => 0])->orderBy(['topic.id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
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
        $this->canonical = Yii::$app->params['domain'].'search';

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

}


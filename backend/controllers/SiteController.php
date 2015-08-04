<?php
namespace backend\controllers;

use common\models\Reply;
use common\models\Topic;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends BackendController
{
    public $title = '云建议';
    public $description = '云建议';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'clean-cache'],
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
        ];
    }

    public function actionIndex()
    {
        //今天注册用户
        $model['userToday'] = User::find()->where(['between', 'created_at', strtotime(date('Y-m-d', time())), strtotime(date('Y-m-d', time())) + 86400])->count();

        //今天建议
        $model['topicToday'] = Topic::find()->where(['between', 'created', strtotime(date('Y-m-d', time())), strtotime(date('Y-m-d', time())) + 86400])->count();

        //今天回复
        $model['replyToday'] = Reply::find()->where(['between', 'created', strtotime(date('Y-m-d', time())), strtotime(date('Y-m-d', time())) + 86400])->count();

        //7天内注册用户
        $model['user7day'] = User::find()->where(['between', 'created_at', strtotime(date('Y-m-d', time())) - 86400 * 7, strtotime(date('Y-m-d', time()))])->count();

        //7天内建议
        $model['topic7day'] = Topic::find()->where(['between', 'created', strtotime(date('Y-m-d', time())) - 86400 * 7, strtotime(date('Y-m-d', time()))])->count();

        //7天内回复
        $model['reply7day'] = Reply::find()->where(['between', 'created', strtotime(date('Y-m-d', time())) - 86400 * 7, strtotime(date('Y-m-d', time()))])->count();

        //30天内注册用户
        $model['user30day'] = User::find()->where(['between', 'created_at', strtotime(date('Y-m-d', time())) - 86400 * 30, strtotime(date('Y-m-d', time()))])->count();

        //30天内建议
        $model['topic30day'] = Topic::find()->where(['between', 'created', strtotime(date('Y-m-d', time())) - 86400 * 30, strtotime(date('Y-m-d', time()))])->count();

        //30天内回复
        $model['reply30day'] = Reply::find()->where(['between', 'created', strtotime(date('Y-m-d', time())) - 86400 * 30, strtotime(date('Y-m-d', time()))])->count();

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect('/topic/index');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->loginAdmin()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionCleanCache()
    {
        Yii::$app->cache->flush();
        Yii::$app->getSession()->setFlash('success', '清除所有缓存成功！');
        return $this->goHome();
    }
}

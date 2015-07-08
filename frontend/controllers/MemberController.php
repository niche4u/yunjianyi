<?php
namespace frontend\controllers;

use common\models\Reply;
use common\models\Topic;
use common\models\User;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class MemberController extends Controller
{
    public $title = '';
    public $description = '';

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

    public function actionIndex($username)
    {
        $this->title = $username.' - '.Yii::$app->name;
        $this->description = '';

        $model = $this->findModel($username);
        return $this->render('index', ['model' => $model]);
    }

    public function actionReply($username, $page = null)
    {
        $this->title = $username.'发布的回复 - '.Yii::$app->name;
        $this->description = '';

        $user = $this->findModel($username);
        $query = Reply::find()->where(['user_id' => $user->id]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $model = $query->orderBy(['id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('reply', ['model' => $model, 'user' => $user, 'pagination'=> $pagination]);
    }

    public function actionTopic($username)
    {
        $this->title = $username.'发布的主题 - '.Yii::$app->name;
        $this->description = '';

        $user = $this->findModel($username);
        $query = Topic::find()->where(['user_id' => $user->id]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $model = $query->orderBy(['id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('topic', ['model' => $model, 'user' => $user, 'pagination'=> $pagination]);
    }

    /**
     * Finds the Topic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Topic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($username)
    {
        if (($model = User::findOne(['username' => $username])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

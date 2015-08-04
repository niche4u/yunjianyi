<?php
namespace frontend\controllers;

use common\models\Follow;
use Yii;
use yii\web\Controller;

/**
 * Follow controller
 */
class FollowController extends Controller
{
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

    public function actionDo($type, $follow)
    {
        $userId = Yii::$app->user->id;
        $model = Follow::findOne(['user_id' => $userId, 'type' => $type, 'follow_id' => $follow]);
        if($model === null) {
            $model = new Follow();
            $model->user_id = $userId;
            $model->follow_id = $follow;
            $model->type = $type;
            $model->save();
        }
        $next = Yii::$app->request->get('next');
        if(isset($next)) return $this->redirect($next);
        else $this->goHome();
    }

    public function actionUndo($type, $follow)
    {
        $userId = Yii::$app->user->id;
        $model = Follow::findOne(['user_id' => $userId, 'type' => $type, 'follow_id' => $follow]);
        if($model !== null) {
            $model->delete();
        }
        $next = Yii::$app->request->get('next');
        if(isset($next)) return $this->redirect($next);
        else $this->goHome();
    }

}

<?php
namespace frontend\controllers;

use common\models\Follow;
use common\models\Topic;
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

        $model = Follow::findOne(['user_id' => Yii::$app->user->id, 'type' => $type, 'follow_id' => $follow]);
        if($model === null) {
            $model = new Follow();
            $model->user_id = Yii::$app->user->id;
            $model->follow_id = $follow;
            $model->type = $type;
            if($model->save() && $type == 3) {
                $topic = Topic::findOne($follow);
                $topic->follow = $topic->follow + 1;
                $topic->save();
            }
        }
        $next = Yii::$app->request->get('next');
        if(isset($next)) return $this->redirect($next);
        else $this->goHome();
    }

    public function actionUndo($type, $follow)
    {
        $model = Follow::findOne(['user_id' => Yii::$app->user->id, 'type' => $type, 'follow_id' => $follow]);
        if($model !== null) {
            if($model->delete() && $type == 3) {
                $topic = Topic::findOne($follow);
                $topic->follow = $topic->follow - 1;
                $topic->save();
            }
        }
        $next = Yii::$app->request->get('next');
        if(isset($next)) return $this->redirect($next);
        else $this->goHome();
    }

}

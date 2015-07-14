<?php
namespace frontend\controllers;

use common\models\Node;
use common\models\Topic;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Node controller
 */
class NodeController extends Controller
{
    public $title = '';
    public $description = '';
    public $bg = null;
    public $bg_color = null;

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

    public function actionView($nodeName)
    {
        $node = $this->findModel($nodeName);

        if($node->need_login == 1 && Yii::$app->user->isGuest) {
            Yii::$app->getSession()->setFlash('danger', '你访问的节点需要登陆之后才能查看');
            return $this->redirect('/account/login?next=/node/'.$nodeName);
        }
        if(!empty($node->bg) && $node->use_bg == 1) $this->bg = $node->bg;
        if(!empty($node->bg_color)) $this->bg_color = $node->bg_color;

        $this->title = $node->name.' - '.Yii::$app->name;
        $this->description = '';

        $SubNode[$node->id] = $node->id;
        $SubSubNode = ArrayHelper::map(Node::find()->where(['parent_id' => $node->id])->andWhere(['is_hidden' => 0])->all(), 'id', 'id');
        array_unique($SubNode, $SubSubNode);

        $query = Topic::find()->where(['in', 'node_id', $SubNode]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $model = $query->orderBy(['id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('view', ['model' => $model, 'node' => $node, 'pagination'=> $pagination]);
    }

    /**
     * Finds the Topic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Topic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = Node::findOne(['enname' => $name])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

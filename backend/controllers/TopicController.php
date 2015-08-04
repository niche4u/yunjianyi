<?php

namespace backend\controllers;

use common\models\Node;
use common\models\User;
use kartik\grid\GridView;
use Yii;
use common\models\Topic;
use common\models\TopicSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Search;

/**
 * TopicController implements the CRUD actions for Topic model.
 */
class TopicController extends BackendController
{
    public $title = '建议管理';
    public $description = '建议管理';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['update', 'index', 'delete', 'create', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Topic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TopicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumns[] = [
            'class'=>'kartik\grid\SerialColumn',
            'contentOptions'=>['class'=>'kartik-sheet-style'],
            'width'=>'36px',
            'header'=>'序号',
            'headerOptions'=>['class'=>'kartik-sheet-style']
        ];
        $gridColumns[] = [
            'attribute'=>'user_id',
            'vAlign'=>'middle',
            'width'=>'180px',
            'value'=>function ($searchModel, $key, $index, $widget) {
                return $searchModel->user->username;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(User::find()->orderBy('username')->asArray()->all(), 'id', 'username'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'用户'],
            'format'=>'raw'
        ];
        $gridColumns[] = [
            'attribute'=>'node_id',
            'vAlign'=>'middle',
            'width'=>'180px',
            'value'=>function ($searchModel, $key, $index, $widget) {
                return $searchModel->node->name;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Node::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '节点'],
            'format' => 'raw'
        ];
        $gridColumns[] = [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'need_login',
            'vAlign'=>'middle',
            'trueLabel' => '是',
            'falseLabel' => '否',
        ];
        $gridColumns[] = 'title';
        $gridColumns[] = 'click';
        $gridColumns[] = 'follow';
        $gridColumns[] = 'reply';
        $gridColumns[] = [
            'mergeHeader' => true,
            'attribute' => 'created',
            'format'=>'datetime'
        ];

        $gridColumns[] = [
            'class' => '\kartik\grid\ActionColumn',
            'template'=>'{view} {update}',
            'buttons'=>[
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->params['domain'].$url, ['title' => '查看', 'target' => '_blank']);
                }
            ]
        ];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns,
        ]);
    }

    /**
     * Displays a single Topic model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Topic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new Topic();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Updates an existing Topic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Topic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->cache->get('topic'.$id);
        $search = Search::findOne($id);
        $search->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Topic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Topic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Topic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

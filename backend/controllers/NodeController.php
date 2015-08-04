<?php

namespace backend\controllers;

use common\models\Tab;
use kartik\grid\GridView;
use Yii;
use common\models\Node;
use common\models\NodeSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * NodeController implements the CRUD actions for Node model.
 */
class NodeController extends BackendController
{
    public $title = '节点管理';
    public $description = '节点管理';

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
     * Lists all Node models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumns[] = [
            'class'=>'kartik\grid\SerialColumn',
            'contentOptions'=>['class'=>'kartik-sheet-style'],
            'width'=>'36px',
            'header'=>'序号',
            'headerOptions'=>['class'=>'kartik-sheet-style']
        ];
        $gridColumns[] = [
            'mergeHeader' => true,
            'attribute' => 'logo24Show',
            'header'=>'图片',
            'format'=>'html'
        ];
        $gridColumns[] = [
            'attribute'=>'id',
            'vAlign'=>'middle',
        ];
        $gridColumns[] = [
            'attribute'=>'name',
            'vAlign'=>'middle',
        ];
        $gridColumns[] = [
            'attribute'=>'tab_id',
            'vAlign'=>'middle',
            'width'=>'180px',
            'value'=>function ($searchModel, $key, $index, $widget) {
                return empty($searchModel->tab->name) ? null : $searchModel->tab->name;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Tab::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'tab'],
            'format' => 'raw'
        ];
        $gridColumns[] = [
            'attribute'=>'enname',
            'vAlign'=>'middle',
        ];
        $gridColumns[] = [
            'attribute'=>'parent_id',
            'vAlign'=>'middle',
            'width'=>'180px',
            'value'=>function ($searchModel, $key, $index, $widget) {
                return empty($searchModel->parent->name) ? null : $searchModel->parent->name;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Node::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '父节点'],
            'format' => 'raw'
        ];
        $gridColumns[] = [
            'attribute'=>'desc',
            'vAlign'=>'middle',
        ];
        $gridColumns[] = [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'is_hidden',
            'width'=>'100px',
            'vAlign'=>'middle',
            'trueLabel' => '是',
            'falseLabel' => '否',
        ];
        $gridColumns[] = [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'need_login',
            'width'=>'100px',
            'vAlign'=>'middle',
            'trueLabel' => '是',
            'falseLabel' => '否',
        ];
        $gridColumns[] = [
            'attribute'=>'sort',
            'vAlign'=>'middle',
        ];
        $gridColumns[] = [
            'mergeHeader' => true,
            'attribute' => 'created',
            'format'=>'datetime'
        ];

        $gridColumns[] = [
            'class' => '\kartik\grid\ActionColumn'
        ];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns,
        ]);
    }

    /**
     * Displays a single Node model.
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
     * Creates a new Node model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Node();

        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Node model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->edit()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Node model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Node model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Node the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Node::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

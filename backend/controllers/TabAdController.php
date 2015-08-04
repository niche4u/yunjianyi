<?php

namespace backend\controllers;

use common\models\Tab;
use Yii;
use common\models\TabAd;
use common\models\TabAdSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\GridView;

/**
 * TabAdController implements the CRUD actions for TabAd model.
 */
class TabAdController extends BackendController
{
    public $title = 'tab广告管理';
    public $description = 'tab广告管理';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all TabAd models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TabAdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumns[] = [
            'class'=>'kartik\grid\SerialColumn',
            'contentOptions'=>['class'=>'kartik-sheet-style'],
            'width'=>'36px',
            'header'=>'序号',
            'headerOptions'=>['class'=>'kartik-sheet-style']
        ];
        $gridColumns[] = [
            'attribute'=>'id',
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
        $gridColumns[] = 'content';
        $gridColumns[] = [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'status',
            'width'=>'100px',
            'vAlign'=>'middle',
            'trueLabel' => '启用',
            'falseLabel' => '禁用',
        ];

        $gridColumns[] = [
            'class' => '\kartik\grid\ActionColumn',
            'template'=>'{view} {update} {delete}',
        ];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gridColumns' => $gridColumns,
        ]);
    }

    /**
     * Displays a single TabAd model.
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
     * Creates a new TabAd model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TabAd();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->cache->delete('TabAd'.$model->tab_id);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TabAd model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->cache->delete('TabAd'.$model->tab_id);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TabAd model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->cache->delete('TabAd'.$model->tab_id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the TabAd model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TabAd the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TabAd::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

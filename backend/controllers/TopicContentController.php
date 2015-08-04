<?php

namespace backend\controllers;

use common\components\Helper;
use common\models\Topic;
use Yii;
use common\models\TopicContent;
use common\models\TopicContentSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\GridView;
use yii\helpers\Markdown;
use yii\web\Response;

/**
 * TopicContentController implements the CRUD actions for TopicContent model.
 */
class TopicContentController extends BackendController
{
    public $title = '建议正文管理';
    public $description = '建议正文管理';

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
     * Lists all TopicContent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TopicContentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumns[] = [
            'class'=>'kartik\grid\SerialColumn',
            'contentOptions'=>['class'=>'kartik-sheet-style'],
            'width'=>'36px',
            'header'=>'序号',
            'headerOptions'=>['class'=>'kartik-sheet-style']
        ];
        $gridColumns[] = [
            'attribute'=>'topic_id',
            'vAlign'=>'middle',
            'width'=>'180px',
            'value'=>function ($searchModel, $key, $index, $widget) {
                return $searchModel->topic->title;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Topic::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '建议标题'],
            'format' => 'raw'
        ];
        $gridColumns[] = [
            'attribute'=>'content',
            'value'=>function ($searchModel, $key, $index, $widget) {
                return Helper::truncateUtf8String($searchModel->content, 100);
            },
        ];
        $gridColumns[] = [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'is_append',
            'vAlign'=>'middle',
            'trueLabel' => '是',
            'falseLabel' => '否',
        ];
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
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->params['domain'].'/topic/'.$model->topic_id, ['title' => '查看', 'target' => '_blank']);
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
     * Displays a single TopicContent model.
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
     * Creates a new TopicContent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TopicContent();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TopicContent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays a single Topic Preview.
     * @param integer $id
     * @return mixed
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionPreview()
    {
        $content = Yii::$app->request->post('content');
        if(Yii::$app->user->isGuest || $content == null) {
            return false;
        }
        else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return HtmlPurifier::process(Markdown::process($content, 'gfm-comment'));
        }
    }

    /**
     * Finds the TopicContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TopicContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TopicContent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

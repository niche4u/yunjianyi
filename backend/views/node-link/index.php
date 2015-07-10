<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NodeLinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Node Links');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-link-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Node Link'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'export' => false,
        'responsive'=>true,
        'hover'=>true
    ]) ?>

</div>

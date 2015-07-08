<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ReplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Replies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reply-index">

    <?= \kartik\grid\GridView::widget([
        'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'export' => false,
        'responsive'=>true,
        'hover'=>true
    ]) ?>

</div>

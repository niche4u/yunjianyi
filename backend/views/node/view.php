<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Node */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'tab_id',
            'name',
            'enname',
            [
                'attribute' => 'parent_id',
                'value' => !empty($model->parent->name) ? $model->parent->name : null,
            ],
            'desc',
            [
                'attribute' => 'logo24Show',
                'format' => 'html',
                'label' => 'logo'
            ],
            'bg',
            'use_bg',
            'bg_color',
            'is_hidden',
            'need_login',
            'sort',
            'created:datetime',
        ],
    ]) ?>

</div>

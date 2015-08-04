<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'avatar24Show',
                'format' => 'html',
                'label' => '头像'
            ],
            [
                'attribute' => 'role',
                'value' => $model->roleName,
            ],
            [
                'attribute' => 'status',
                'value' => $model->statusLabel,
            ],
            'desc',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>

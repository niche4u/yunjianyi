<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Reply */

$this->title = Yii::t('app', 'Update Reply', [
    'modelClass' => 'Reply',
]) . '： ' . $model->topic->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Replies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="reply-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

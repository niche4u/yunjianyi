<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tab */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tab',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tabs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tab-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

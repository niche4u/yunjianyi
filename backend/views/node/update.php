<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Node */

$this->title = Yii::t('app', 'Update Node').': ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="node-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

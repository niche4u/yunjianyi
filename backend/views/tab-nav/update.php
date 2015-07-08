<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TabNav */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tab Nav',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tab Navs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->tab_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tab-nav-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

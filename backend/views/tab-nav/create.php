<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TabNav */

$this->title = Yii::t('app', 'Create Tab Nav');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tab Navs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tab-nav-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = Yii::t('app', 'Create Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

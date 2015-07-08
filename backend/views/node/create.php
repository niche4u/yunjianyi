<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Node */

$this->title = Yii::t('app', 'Create Node');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

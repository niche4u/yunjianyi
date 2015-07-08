<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Topic */

$this->title = Yii::t('app', 'Create Topic');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Topics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

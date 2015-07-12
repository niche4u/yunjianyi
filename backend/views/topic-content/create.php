<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TopicContent */

$this->title = Yii::t('app', 'Create Topic Content');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Topic Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-content-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

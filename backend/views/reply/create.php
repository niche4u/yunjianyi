<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Reply */

$this->title = Yii::t('app', 'Create Reply');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Replies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reply-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

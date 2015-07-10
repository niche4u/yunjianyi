<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TopicContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topic-content-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 16]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::button('预览广告', ['class' => 'btn btn-primary preview']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="row" id="preview"></div>
</div>

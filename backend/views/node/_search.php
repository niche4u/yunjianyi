<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NodeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="node-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tab_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'enname') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'is_hidden') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'created') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

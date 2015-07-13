<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Node */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="node-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'enname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tab_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\common\models\Tab::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'TAB'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'parent_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\common\models\Node::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => '父节点'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logo')->fileInput() ?>

    <?= $form->field($model, 'bg')->fileInput() ?>

    <?= $form->field($model, 'use_bg')->dropDownList(['0' => '否', '1' => '是']) ?>

    <?= $form->field($model, 'bg_color')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_hidden')->dropDownList(['0' => '否', '1' => '是']) ?>

    <?= $form->field($model, 'need_login')->dropDownList(['0' => '否', '1' => '是']) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

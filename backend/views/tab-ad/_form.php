<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TabAd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tab-ad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tab_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\common\models\Tab::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'TAB'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'status')->dropDownList(['0' => '否', '1' => '是']) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::button('预览', ['class' => 'btn btn-primary preview']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="row" id="preview"></div>
</div>

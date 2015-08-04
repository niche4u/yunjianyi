<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

?>
<section>
    <?= yii\widgets\Breadcrumbs::widget([
        'options' => [
            'class' => 'breadcrumb mb0',
        ],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <article class="header">
        <div class="row">
            <div class="col-lg-12">
                <?php $form = ActiveForm::begin(['id' => 'create-form']); ?>
                <?= $form->field($model, 'title') ?>
                <?= $form->field($model, 'node_id')->widget(Select2::classname(), [
                    'data' => \common\models\Node::AllNode(),
                    'options' => ['placeholder' => '请选择一个节点'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>
                <?= $form->field($topicContent, 'content')->textarea(['rows' => 16]) ?>
                <div class="form-group">
                    <?= Html::submitButton('提交建议', ['class' => 'btn btn-success']) ?>
                    <?= Html::button('预览正文', ['class' => 'btn btn-primary preview']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </article>
    <div class="row" id="preview"></div>
</section>
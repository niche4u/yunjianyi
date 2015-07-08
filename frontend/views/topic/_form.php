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
                    'data' => \yii\helpers\ArrayHelper::map(\common\models\Node::find()->all(), 'id', 'name'),
                    'options' => ['placeholder' => '请选择一个节点'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>
                <?= $form->field($topicContent, 'content')->textarea(['rows' => 16]) ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '发布主题' : '更新主题', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </article>
</section>
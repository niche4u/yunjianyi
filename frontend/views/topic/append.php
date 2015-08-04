<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = '添加附言';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-9">
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
                        <div class="form-group field-topic-title">
                            <label class="control-label" for="topic-title">标题</label>
                            <p><?= $model->title?></p>
                        </div>
                        <?= $form->field($topicContent, 'content')->textarea(['rows' => 16])->label('附言正文') ?>
                        <div class="form-group">
                            <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
                            <?= Html::button('预览正文', ['class' => 'btn btn-primary preview']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </article>
            <div class="row" id="preview"></div>
        </section>
    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
        <section>
            <div class="block-header">
                <small>提示</small>
            </div>
            <div class="block-content padding-left-0">
                <ul>
                    <li>请尽量一句话描述建议要点。</li>
                    <li>正文可以为空。</li>
                    <li>正文支持 GitHub Flavored Markdown 文本标记语法。</li>
                    <li>请为你的建议选择一个合适的节点。</li>
                    <li>发布后就<strong>不能更改</strong>。</li>
                    <li><strong>60分钟</strong> 后能对建议添加附言。</li>
                    <li>发布明显恶意的广告信息、敏感内容，垃圾信息将会被移除。</li>
                </ul>
            </div>
        </section>
    </div>

</div>


<?php
$sendJs = "\n
  $('.preview').click(function(){
     $('#topiccontent-content').val();
     $.ajax({
      url:'/topic/preview',
      type: 'POST',
      data: {content:$('#topiccontent-content').val()},
      success: function(json) {
          $('#preview').html('<div class=\"col-lg-12\"><article class=\"header topic-body markdown-content\">'+json+'</article></div>')
        },
    });
  });";
$this->registerJs($sendJs, \yii\web\View::POS_READY);
?>
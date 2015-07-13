<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TopicContent */

$this->title = Yii::t('app', 'Update Topic Content:') . ' ' . $model->topic->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Topic Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="topic-content-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
$sendJs = "\n
  $('.preview').click(function(){
     $('#topiccontent-content').val();
     $.ajax({
      url:'/topic-content/preview',
      type: 'POST',
      data: {content:$('#topiccontent-content').val()},
      success: function(json) {
          $('#preview').html('<div class=\"col-lg-12\"><article class=\"header topic-body markdown-content\">'+json+'</article></div>')
        },
    });
  });";
$this->registerJs($sendJs, \yii\web\View::POS_READY);
?>

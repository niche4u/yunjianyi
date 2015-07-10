<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NodeLink */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Node Link',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Node Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="node-link-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
$sendJs = "\n
  $('.preview').click(function(){
     $('#nodelink-content').val();
     $.ajax({
      url:'/topic-content/preview',
      type: 'POST',
      data: {content:$('#nodelink-content').val()},
      success: function(json) {
          $('#preview').html('<div class=\"col-lg-12\"><article class=\"header topic-body markdown-content\">'+json+'</article></div>')
        },
    });
  });";
$this->registerJs($sendJs, \yii\web\View::POS_READY);
?>

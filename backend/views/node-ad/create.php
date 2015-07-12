<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NodeAd */

$this->title = Yii::t('app', 'Create Node Ad');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Node Ads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-ad-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
$sendJs = "\n
  $('.preview').click(function(){
     $('#nodead-content').val();
     $.ajax({
      url:'/topic-content/preview',
      type: 'POST',
      data: {content:$('#nodead-content').val()},
      success: function(json) {
          $('#preview').html('<div class=\"col-lg-12\"><article class=\"header topic-body markdown-content\">'+json+'</article></div>')
        },
    });
  });";
$this->registerJs($sendJs, \yii\web\View::POS_READY);
?>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TabAd */

$this->title = Yii::t('app', 'Create Tab Ad');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tab Ads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tab-ad-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
$sendJs = "\n
  $('.preview').click(function(){
     $('#tabad-content').val();
     $.ajax({
      url:'/topic-content/preview',
      type: 'POST',
      data: {content:$('#tabad-content').val()},
      success: function(json) {
          $('#preview').html('<div class=\"col-lg-12\"><article class=\"header topic-body markdown-content\">'+json+'</article></div>')
        },
    });
  });";
$this->registerJs($sendJs, \yii\web\View::POS_READY);
?>

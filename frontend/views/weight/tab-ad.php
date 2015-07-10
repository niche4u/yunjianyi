<?php

use yii\helpers\Markdown;

$TabAd = \common\models\TabAd::TabAd(Yii::$app->session->get('tab'));

?>
<?php if(!empty($TabAd)):?>
<section class="mt20">
    <div class="block-content markdown-content">
        <?php foreach ($TabAd as $ad):?>
        <?= Markdown::process($ad['content'], 'gfm')?>
        <?php endforeach?>
    </div>
</section>
<?php endif?>

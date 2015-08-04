<?php

$TabAd = \common\models\TabAd::TabAd(Yii::$app->session->get('tab'));

?>
<?php if(!empty($TabAd)):?>
<section>
    <div class="block-content markdown-content">
        <?php foreach ($TabAd as $ad):?>
            <?= $ad['content']?>
        <?php endforeach?>
    </div>
</section>
<?php endif?>

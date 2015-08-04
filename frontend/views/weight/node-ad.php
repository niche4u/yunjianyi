<?php

$NodeAd = \common\models\NodeAd::NodeAd($node);

?>
<?php if(!empty($NodeAd)):?>
<section>
    <div class="block-content markdown-content">
        <?php foreach ($NodeAd as $ad):?>
        <?= $ad['content']?>
        <?php endforeach?>
    </div>
</section>
<?php endif?>

<?php

use yii\helpers\Markdown;

$NodeAd = \common\models\NodeAd::NodeAd($node->id);

?>
<?php if(!empty($NodeAd)):?>
<section class="mt20">
    <div class="block-content markdown-content">
        <?php foreach ($NodeAd as $ad):?>
        <?= nl2br(Markdown::process($ad['content'], 'gfm'))?>
        <?php endforeach?>
    </div>
</section>
<?php endif?>

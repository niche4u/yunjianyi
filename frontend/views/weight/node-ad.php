<?php

use yii\helpers\Markdown;

$NodeAd = \common\models\NodeAd::NodeAd($node_id);

?>
<?php if(!empty($NodeAd)):?>
<section>
    <div class="block-content markdown-content">
        <?php foreach ($NodeAd as $ad):?>
        <?= Markdown::process($ad['content'], 'gfm')?>
        <?php endforeach?>
    </div>
</section>
<?php endif?>

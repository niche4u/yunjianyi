<?php

use yii\helpers\Markdown;

$NodeLink = \common\models\NodeLink::NodeLink($node->id);

?>
<?php if(!empty($NodeLink)):?>
<section class="mt20">
    <div class="block-content markdown-content">
        <?php foreach ($NodeLink as $link):?>
        <?= nl2br(Markdown::process($link['content'], 'gfm'))?>
        <?php endforeach?>
    </div>
</section>
<?php endif?>

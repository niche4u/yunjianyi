<?php

use yii\helpers\Markdown;

$NodeLink = \common\models\NodeLink::NodeLink($node);

?>
<?php if(!empty($NodeLink)):?>
<section>
    <div class="block-content markdown-content">
        <?php foreach ($NodeLink as $link):?>
        <?= Markdown::process($link['content'], 'gfm-comment')?>
        <?php endforeach?>
    </div>
</section>
<?php endif?>

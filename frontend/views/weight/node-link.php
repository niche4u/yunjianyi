<?php

use yii\helpers\Markdown;

$NodeLink = \common\models\NodeLink::NodeLink($node_id);

?>
<?php if(!empty($NodeLink)):?>
<section>
    <div class="block-content markdown-content">
        <?php foreach ($NodeLink as $link):?>
        <?= Markdown::process($link['content'], 'gfm')?>
        <?php endforeach?>
    </div>
</section>
<?php endif?>

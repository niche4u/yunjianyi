<?php

$NodeLink = \common\models\NodeLink::NodeLink($node);

?>
<?php if(!empty($NodeLink)):?>
<section>
    <div class="block-content markdown-content">
        <?php foreach ($NodeLink as $link):?>
        <?= $link['content']?>
        <?php endforeach?>
    </div>
</section>
<?php endif?>

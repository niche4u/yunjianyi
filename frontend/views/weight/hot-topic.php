<?php $HotTopic = \common\models\Topic::HotTopic();?>
<?php if(!empty($HotTopic)):?>
<section>
    <div class="block-header">今日热门主题</div>
    <div class="mt5"></div>
    <?php foreach ($HotTopic as $topic):?>
        <article class="sidebar block-hot-topic">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody><tr>
                    <td width="24" valign="middle" align="center">
                        <a href="/member/<?= $topic->user->username?>"><img src="<?= $topic->user->avatar24?>" class="img-rounded"></a>
                    </td>
                    <td width="10"></td>
                    <td width="auto" valign="middle">
                <span class="hot_topic">
                <a href="/topic/<?= $topic->id?>"><?= $topic->title?></a>
                </span>
                    </td>
                </tr>
                </tbody></table>
        </article>
    <?php endforeach?>
</section>
<?php endif?>

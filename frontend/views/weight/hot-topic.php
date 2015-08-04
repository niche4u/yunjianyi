<?php $HotTopic = \common\models\Topic::HotTopic();?>
<?php if(!empty($HotTopic)):?>
<section>
    <div class="block-header"><small>今日热门建议</small></div>
    <div class="mt5"></div>
    <?php foreach ($HotTopic as $topic):?>
        <article class="sidebar block-hot-topic">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody><tr>
                    <td width="24" valign="middle" align="center">
                        <a href="/member/<?= $topic['username']?>"><img src="<?= Yii::$app->params['avatarUrl'].'/24/'.$topic['avatar']?>" class="img-rounded"></a>
                    </td>
                    <td width="10"></td>
                    <td width="auto" valign="middle">
                <span class="hot_topic">
                <a href="/topic/<?= $topic['id']?>"><?= $topic['title']?></a>
                </span>
                    </td>
                </tr>
                </tbody></table>
        </article>
    <?php endforeach?>
</section>
<?php endif?>

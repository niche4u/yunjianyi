<?php $ranking = \common\models\Topic::Ranking();?>
<?php if(!empty($ranking)):?>
<section>
    <div class="block-header"><small>提建议排行榜</small></div>

    <?php foreach ($ranking as $r):?>
        <article class="sidebar block-hot-topic">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody>
                <tr>
                    <td width="24" valign="middle" align="center">
                        <a href="/member/<?= $r['username']?>"><img src="<?= Yii::$app->params['avatarUrl'].'/24/'.$r['avatar']?>" class="img-rounded"></a>
                    </td>
                    <td width="10"></td>
                    <td width="auto" valign="middle"><span class="hot_topic"><a href="/member/<?= $r['username']?>"><?= $r['username']?></a></span></td>
                    <td width="auto" valign="right" align="right"><small><?= $r['topic_count']?></small></td>
                </tr>
                </tbody></table>
        </article>
    <?php endforeach?>
</section>
<?php endif?>
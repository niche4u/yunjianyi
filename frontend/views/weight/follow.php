<?php $follow = \common\models\Follow::User(false);?>
<?php if(!empty($follow)):?>
<section>
    <div class="block-header"><small>我关注的人</small></div>
    <?php foreach ($follow as $f):?>
        <article class="sidebar block-hot-topic">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody><tr>
                    <td width="24" valign="middle" align="center">
                        <a href="/member/<?= $f['username']?>"><img src="<?= Yii::$app->params['avatarUrl'].'/24/'.$f['avatar']?>" class="img-rounded"></a>
                    </td>
                    <td width="10"></td>
                    <td width="auto" valign="middle">
                <span class="hot_topic">
                <a href="/member/<?= $f['username']?>"><?= $f['username']?></a>
                </span>
                    </td>
                </tr>
                </tbody></table>
        </article>
    <?php endforeach?>
</section>
<?php endif?>
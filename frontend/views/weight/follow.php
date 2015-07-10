<section>
    <div class="block-header">我关注的人</div>
    <?php $follow = \common\models\Follow::findAll(['user_id' => Yii::$app->user->id, 'type' => 1]);?>
    <?php foreach ($follow as $f):?>
        <article class="sidebar block-hot-topic">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody><tr>
                    <td width="24" valign="middle" align="center">
                        <a href="/member/<?= $f->user->username?>"><img src="<?= $f->user->avatar24?>" class="img-rounded"></a>
                    </td>
                    <td width="10"></td>
                    <td width="auto" valign="middle">
                <span class="hot_topic">
                <a href="/member/<?= $f->user->username?>"><?= $f->user->username?></a>
                </span>
                    </td>
                </tr>
                </tbody></table>
        </article>
    <?php endforeach?>
</section>
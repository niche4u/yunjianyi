<section class="mt20">
    <div class="block-header">我收藏的节点</div>
    <?php $follow = \common\models\Follow::findAll(['user_id' => Yii::$app->user->id, 'type' => 2]);?>
    <?php foreach ($follow as $f):?>
        <article class="sidebar">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody><tr>
                    <td width="24" valign="middle" align="center">
                        <a href="/node/<?= $f->node->enname?>"><img src="<?= $f->node->logo24?>" class="img-rounded"></a>
                    </td>
                    <td width="10"></td>
                    <td width="auto" valign="middle">
                <a href="/node/<?= $f->node->enname?>"><?= $f->node->name?></a>
                    </td>
                </tr>
                </tbody></table>
        </article>
    <?php endforeach?>
</section>

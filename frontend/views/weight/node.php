<?php $follow = \common\models\Follow::Node(false);?>
<?php if(!empty($follow)):?>
<section>
    <div class="block-header"><small>我收藏的节点</small></div>
    <?php $follow = \common\models\Follow::Node(false);?>
    <?php foreach ($follow as $f):?>
        <article class="sidebar">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody><tr>
                    <td width="24" valign="middle" align="center">
                        <a href="/node/<?= $f['enname']?>"><img src="<?= Yii::$app->params['nodeUrl'].'/24/'.$f['logo']?>" class="img-rounded"></a>
                    </td>
                    <td width="10"></td>
                    <td width="auto" valign="middle">
                <a href="/node/<?= $f['enname']?>"><?= $f['name']?></a>
                    </td>
                </tr>
                </tbody></table>
        </article>
    <?php endforeach?>
</section>
<?php endif?>
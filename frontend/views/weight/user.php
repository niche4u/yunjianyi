<?php if(Yii::$app->user->isGuest):?>
    <section>
        <div class="block-content">
            <strong><?= Yii::$app->name?>&nbsp;&nbsp;&nbsp;&nbsp;way to sex</strong>
            <div class="mt6"></div>
            <small>V2SEX，一个很实在的技术宅的专属社区。在这里交流技术，各种创意点子，聊妹子，聊男人，同性恋，各种兴趣爱好，提问，甚至可以交流AV。</small>
        </div>
    </section>
<?php else:?>

    <section>
        <article class="block-header">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody><tr>
                    <td width="48" valign="top"><a href="/member/<?= Yii::$app->user->identity->username?>"><img src="<?= Yii::$app->user->identity->avatar48?>" class="img-rounded"></a></td>
                    <td width="10" valign="top"></td>
                    <td width="auto" align="left"><h4><a href="/member/<?= Yii::$app->user->identity->username?>"><?= Yii::$app->user->identity->username?></a></h4></td></tr>
                </tbody>
            </table>
        </article>
        <div class="block-header">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody>
                <td width="33%" align="center"><a href="/account/node" class="small"><strong><?= \common\models\Follow::find()->where(['user_id' => Yii::$app->user->id, 'type' => 2])->count()?></strong><div class="mt3"></div><small>节点收藏</small></a></td>
                <td width="34%" align="center"><a href="/account/topic" class="small"><strong><?= \common\models\Follow::find()->where(['user_id' => Yii::$app->user->id, 'type' => 3])->count()?></strong><div class="mt3"></div><small>主题收藏</small></a></td>
                <td width="33%" align="center"><a href="/account/follow" class="small"><strong><?= \common\models\Follow::find()->where(['user_id' => Yii::$app->user->id, 'type' => 1])->count()?></strong><div class="mt3"></div><small>关注的人</small></a></td>
                </tbody>
            </table>
        </div>
        <?php if(Yii::$app->user->identity->email_status == 1):?>
        <div class="block-content">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody><tr>
                    <td width="15"></td>
                    <td width="auto" valign="middle" align="left"><a href="/create"><i class="glyphicon glyphicon-pencil"></i> 创作新主题</a></td></tr>
                </tbody>
            </table>
        </div>
        <?php endif?>
    </section>
<?php endif?>

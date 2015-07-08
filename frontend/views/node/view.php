<?php
$this->title = $node->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $agent = \common\components\Helper::agent();?>
<div class="row">
    <div class="col-md-9">
        <section>
            <?= yii\widgets\Breadcrumbs::widget([
                'options' => [
                    'class' => 'breadcrumb mb0',
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <article class="item">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody><tr>
                        <td width="80" valign="top" align="left"><img src="<?= $node->logo80?>" class="img-rounded"></td>
                        <td width="10"></td>
                        <td width="auto" valign="right">
                            <div class="pull-right text-right">
                                <?php if(!Yii::$app->user->isGuest):?>
                                <?php if(\common\models\Follow::findOne(['user_id' => Yii::$app->user->id, 'follow_id' => $node->id, 'type' => 2]) === null):?>
                                    <button type="button" class="btn btn-success btn-xs" name="setting-button" onclick="location.href = '/follow/do/2/<?= $node->id?>?next=/node/<?= $node->enname?>';">加入收藏</button>
                                <?php else:?>
                                    <button type="button" class="btn btn-default btn-xs" name="setting-button" onclick="location.href = '/follow/undo/2/<?= $node->id?>?next=/node/<?= $node->enname?>';">取消收藏</button>
                                <?php endif?>
                                <?php endif?>
                                <div class="mt10"></div>
                                </div>
                            <h2><?= $node->name?></h2>
                            <small><?= $node->desc?></small>
                            <?php if(!Yii::$app->user->isGuest):?>
                            <div class="mt10"></div>
                            <button type="button" class="btn btn-default btn-xs" name="setting-button" onclick="location.href = '/create/<?= $node->enname?>';">创建新主题</button>
                            <?php endif?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </article>
            <?php foreach($model as $t):?>
                <article class="item">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody><tr>
                            <?php if($agent == 'is_iphone' || $agent == 'is_android'):?>
                            <td width="24" valign="middle" align="center"><a href="/member/<?= $t->user->username?>"><img src="<?= $t->user->avatar24?>" class="img-rounded"></a></td>
                            <td width="10"></td>
                            <td width="auto" valign="middle">
                                <small>
                                    <strong><a href="/member/<?= $t->user->username?>"><?= $t->user->username?></a></strong>
                                </small>
                                <div class="mt5"></div>
                                <h2><a href="/topic/<?= $t->id?><?php if($t->reply > 0):?>#reply<?= $t->reply?><?php endif?>"><?= $t->title?></a></h2>
                                <small>
                                    <?php if(!empty($t->last_reply_time)):?><?= Yii::$app->formatter->asRelativeTime($t->last_reply_time)?> &nbsp;•&nbsp; 最后回复 <strong><a href="/member/<?= $t->lastReplyUser->username?>"><?= $t->lastReplyUser->username?></a></strong>
                                    <?php else:?>
                                        <?= Yii::$app->formatter->asRelativeTime($t->created)?>
                                    <?php endif?>
                                </small>
                            </td>
                            <?php else:?>
                                <td width="48" valign="middle" align="center"><a href="/member/<?= $t->user->username?>"><img src="<?= $t->user->avatar48?>" class="img-rounded"></a></td>
                                <td width="10"></td>
                                <td width="auto" valign="middle">
                                    <h2><a href="/topic/<?= $t->id?><?php if($t->reply > 0):?>#reply<?= $t->reply?><?php endif?>"><?= $t->title?></a></h2>
                                    <small><strong><a href="/member/<?= $t->user->username?>"><?= $t->user->username?></a></strong>
                                        <?php if(!empty($t->last_reply_time)):?>&nbsp;•&nbsp; <?= Yii::$app->formatter->asRelativeTime($t->last_reply_time)?> &nbsp;•&nbsp; 最后回复 <strong><a href="/member/<?= $t->lastReplyUser->username?>"><?= $t->lastReplyUser->username?></a></strong>
                                        <?php else:?>
                                            &nbsp;•&nbsp; <?= Yii::$app->formatter->asRelativeTime($t->created)?>
                                        <?php endif?>
                                    </small>
                                </td>
                            <?php endif?>
                            <td width="50" align="right" valign="middle">
                                <?php if($t->reply > 0):?><a href="/topic/<?= $t->id?>#reply<?= $t->reply?>" class="badge"><?= $t->reply?></a><?php endif?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </article>
            <?php endforeach?>
            <article>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination'=>$pagination,
                ]);?>
            </article>
        </section>
    </div>

    <div class="col-md-3 sidebar hidden-xs hidden-sm">
        <?= $this->render('@frontend/views/weight/user')?>
        <?= $this->render('@frontend/views/weight/related-node', ['node' => $node])?>
    </div>
</div>

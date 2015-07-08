<?php
use common\components\Helper;

$agent = \common\components\Helper::agent();
?>
<div class="row">
    <div class="col-md-9">
        <section>
            <article class="item">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody><tr>
                        <?php if($agent == 'is_iphone' || $agent == 'is_android'):?>
                            <td width="48" valign="top" align="left"><img src="<?= $model->avatar48?>" class="img-rounded"></td>
                        <?php else:?>
                            <td width="80" valign="top" align="left"><img src="<?= $model->avatar80?>" class="img-rounded"></td>
                        <?php endif?>
                        <td width="10"></td>
                        <td width="auto" valign="right">
                            <?php if(Yii::$app->user->isGuest || (Yii::$app->user->id != $model->id)):?>
                            <div class="pull-right text-right">
                                <?php if(!Yii::$app->user->isGuest):?>
                                <?php if(\common\models\Follow::findOne(['user_id' => Yii::$app->user->id, 'follow_id' => $model->id, 'type' => 1]) === null):?>
                                <button type="button" class="btn btn-success btn-xs" name="setting-button" onclick="location.href = '/follow/do/1/<?= $model->id?>?next=/member/<?= $model->username?>';">加入特别关注</button>
                                <?php else:?>
                                <button type="button" class="btn btn-default btn-xs" name="setting-button" onclick="location.href = '/follow/undo/1/<?= $model->id?>?next=/member/<?= $model->username?>';">取消特别关注</button>
                                <?php endif?>
                                <?php endif?>
                            </div>
                            <?php endif?>
                            <h1>
                            <?= $model->username?>
                            <?php if(!empty($model->homepage)):?>
                            <small><a href="<?= $model->homepage?>" target="_blank" rel="nofollow"><?= $model->homepage?></a></small>
                            <?php endif?>
                            </h1>
                            <small><?= Yii::$app->name?> 第 <?= $model->id?> 号会员，加入为 <?= date('Y-m-d H:i:s', $model->created_at)?>
                                <?php if(!empty($model->area)):?>，所在地 <?= $model->area?><?php endif?>
                            </small>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </article>
            <?php if(!empty($model->desc)):?>
            <article class="item">
                <div class="mt10"></div>
                <p><?= nl2br($model->desc)?></p>
            </article>
            <?php endif?>
        </section>

        <section class="mt20">
            <div class="block-header"><?= $model->username?> 最近发布的主题</div>
            <?php foreach($model->topicList as $t):?>
                <?php $nodeInfo = \common\models\Node::Info($t->node_id);?>
            <article class="item">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody><tr>
                        <td width="auto" valign="middle">

                            <h2><a href="/topic/<?= $t->id?><?php if($t->reply > 0):?>#reply<?= $t->reply?><?php endif?>"><?= $t->title?></a></h2>
                            <small><a class="node" href="/node/<?= $nodeInfo['enname']?>"><?= $nodeInfo['name']?></a> &nbsp;•&nbsp; <strong><a href="/member/<?= $model->username?>"><?= $model->username?></a></strong>
                                <?php if(!empty($t->last_reply_time)):?>&nbsp;•&nbsp; <?= Yii::$app->formatter->asRelativeTime($t->last_reply_time)?> &nbsp;•&nbsp; 最后回复 <strong><a href="/member/<?= $t->lastReplyUser->username?>"><?= $t->lastReplyUser->username?></a></strong>
                                <?php else:?>
                                    &nbsp;•&nbsp; <?= Yii::$app->formatter->asRelativeTime($t->created)?>
                                <?php endif?>
                            </small>

                        </td>
                        <td width="50" align="right" valign="middle">
                            <?php if($t->reply > 0):?><a href="/topic/<?= $t->id?>#reply<?= $t->reply?>" class="badge"><?= $t->reply?></a><?php endif?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </article>
            <?php endforeach?>
            <div class="block-footer"><a href="/member/<?= $model->username?>/topic"><?= $model->username?> 发布的更多主题</a></div>
        </section>

        <section class="mt20">
            <div class="block-header"><?= $model->username?>最近的回复</div>
            <?php foreach($model->replyList as $c):?>
                <?php $topicInfo = \common\models\Topic::Info($c->topic_id);?>
                <article class="member-reply">
                    <div class="member-reply-body">
                        <div class="member-reply-author">
                            <aside><small>回复了 <a href="/member/<?= $topicInfo['username']?>"><?= $topicInfo['username']?></a> 创建的主题 <a href="/topic/<?= $c->topic_id?>"> <?= $topicInfo['title']?></a> &nbsp;&nbsp;<?= Yii::$app->formatter->asRelativeTime($c->created)?></small></aside>
                        </div>
                        <div class="mt10"><p><?= Helper::autolink($c->content)?></p></div>
                        <div class="clearfix"></div>
                    </div>
                </article>
            <?php endforeach?>
            <div class="block-footer"><a href="/member/<?= $model->username?>/reply"><?= $model->username?> 的更多回复</a></div>
        </section>
    </div>

    <div class="col-md-3 sidebar hidden-xs hidden-sm">
        <?= $this->render('@frontend/views/weight/user')?>
    </div>
</div>

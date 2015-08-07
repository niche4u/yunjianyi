<?php

?>
<div class="row">
    <div class="col-md-9">
        <section>
            <article class="item">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody><tr>
                        <?php if($this->context->agent == 'is_iphone' || $this->context->agent == 'is_android'):?>
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
                            <h2>
                            <?= $model->username?>
                            </h2>
                            <small><?= Yii::$app->name?> 第 <?= $model->id?> 号会员，加入为 <?= Yii::$app->formatter->asDatetime($model->created_at);?>
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
                <p><?= \common\components\Helper::autoLink(\yii\helpers\HtmlPurifier::process(\yii\helpers\Markdown::process($model->desc, 'gfm-comment')))?></p>
            </article>
            <?php endif?>
        </section>

        <section>
            <div class="block-header"><small><?= $model->username?> 最近提的建议</small></div>
            <?php foreach($model->topicList as $t):?>
            <article class="item">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody><tr>
                        <td width="auto" valign="middle">

                            <h2><a href="/topic/<?= $t['id']?><?php if($t['reply'] > 0):?>#reply<?= $t['reply']?><?php endif?>"><?= $t['title']?></a></h2>
                            <small>
                                <a class="node" href="/node/<?= $t['enname']?>"><?= $t['name']?></a> &nbsp;•&nbsp;
                                <?php if(!empty($t['last_reply_time'])):?><?= Yii::$app->formatter->asRelativeTime($t['last_reply_time'])?> &nbsp;•&nbsp; 最后回复 <strong><a href="/member/<?= $t['last_reply_user']?>"><?= $t['last_reply_user']?></a></strong>
                                <?php else:?>
                                    <?= Yii::$app->formatter->asRelativeTime($t['created'])?>
                                <?php endif?>
                            </small>

                        </td>
                        <td width="50" align="right" valign="middle">
                            <?php if($t['reply'] > 0):?><a href="/topic/<?= $t['id']?>#reply<?= $t['reply']?>" class="badge"><?= $t['reply']?></a><?php endif?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </article>
            <?php endforeach?>
            <div class="block-footer"><a href="/member/<?= $model->username?>/topic"><?= $model->username?> 提的更多建议</a></div>
        </section>

        <section>
            <div class="block-header"><small><?= $model->username?>最近的回复</small></div>
            <?php foreach($model->replyList as $c):?>
                <article class="member-reply">
                    <div class="member-reply-body">
                        <div class="member-reply-author">
                            <aside><small>回复了 <a href="/member/<?= $c['username']?>"><?= $c['username']?></a> 提的建议 <a href="/topic/<?= $c['topic_id']?>"> <?= $c['title']?></a> &nbsp;&nbsp;<?= Yii::$app->formatter->asRelativeTime($c['created'])?></small></aside>
                        </div>
                        <div class="mt10 markdown-content"><?= \common\components\Helper::autoLink(\yii\helpers\HtmlPurifier::process(\yii\helpers\Markdown::process($c['content'], 'gfm-comment')))?></div>
                        <div class="clearfix"></div>
                    </div>
                </article>
            <?php endforeach?>
            <div class="block-footer"><a href="/member/<?= $model->username?>/reply"><?= $model->username?> 的更多回复</a></div>
        </section>
    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
    </div>
</div>

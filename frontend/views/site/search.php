<?php

$this->params['breadcrumbs'][] = '搜索';
$this->params['breadcrumbs'][] = $keyword;

$agent = \common\components\Helper::agent();
?>
    <div class="row">
        <div class="col-md-9">
            <section>
                <?= yii\widgets\Breadcrumbs::widget([
                    'options' => [
                        'class' => 'breadcrumb mb0',
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?php foreach($topic as $t):?>
                    <?php $topicInfo = \common\models\Topic::Info($t['topic_id']);?>
                    <?php $userInfo = \common\models\User::Info($topicInfo['user_id']);?>
                    <?php $lastReplyUser = \common\models\User::Info($topicInfo['last_reply_user']);?>
                <article class="item">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody><tr>
                            <?php if($agent == 'is_iphone' || $agent == 'is_android'):?>
                                <td width="24" valign="middle" align="center"><a href="/member/<?= $userInfo['username']?>"><img src="<?= $userInfo['avatar24']?>" class="img-rounded"></a></td>
                                <td width="10"></td>
                                <td width="auto" valign="middle">
                                    <small>
                                        <a class="node" href="/node/<?= $topicInfo['nodeenname']?>"><?= $topicInfo['nodename']?></a> &nbsp;•&nbsp; <strong><a href="/member/<?= $userInfo['username']?>"><?= $userInfo['username']?></a></strong>
                                    </small>
                                    <div class="mt5"></div>
                                    <h2><a href="/topic/<?= $topicInfo['id']?>#reply<?= $topicInfo['reply']?>"><?= $topicInfo['title']?></a></h2>
                                    <small>
                                        <?php if(!empty($topicInfo['last_reply_time'])):?><?= Yii::$app->formatter->asRelativeTime($topicInfo['last_reply_time'])?> &nbsp;•&nbsp; 最后回复 <strong><a href="/member/<?= $lastReplyUser['username']?>"><?= $lastReplyUser['username']?></a></strong>
                                        <?php else:?>
                                            <?= Yii::$app->formatter->asRelativeTime($topicInfo['created'])?>
                                        <?php endif?>
                                    </small>
                                </td>
                            <?php else:?>
                                <td width="48" valign="middle" align="center"><a href="/member/<?= $userInfo['username']?>"><img src="<?= $userInfo['avatar48']?>" class="img-rounded"></a></td>
                                <td width="10"></td>
                                <td width="auto" valign="middle">
                                    <h2><a href="/topic/<?= $topicInfo['id']?>#reply<?= $topicInfo['reply']?>"><?= $topicInfo['title']?></a></h2>
                                    <small><a class="node" href="/node/<?= $topicInfo['nodeenname']?>"><?= $topicInfo['nodename']?></a> &nbsp;•&nbsp; <strong><a href="/member/<?= $userInfo['username']?>"><?= $userInfo['username']?></a></strong>
                                        <?php if(!empty($topicInfo['last_reply_time'])):?>&nbsp;•&nbsp; <?= Yii::$app->formatter->asRelativeTime($topicInfo['last_reply_time'])?> &nbsp;•&nbsp; 最后回复 <strong><a href="/member/<?= $lastReplyUser['username']?>"><?= $lastReplyUser['username']?></a></strong>
                                        <?php else:?>
                                            &nbsp;•&nbsp; <?= Yii::$app->formatter->asRelativeTime($topicInfo['created'])?>
                                        <?php endif?>
                                    </small>
                                </td>
                            <?php endif?>
                            <td width="50" align="right" valign="middle">
                                <?php if($topicInfo['reply'] > 0):?><a href="/topic/<?= $topicInfo['id']?>#reply<?= $topicInfo['reply']?>" class="badge"><?= $topicInfo['reply']?></a><?php endif?>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                </article>
                <?php endforeach?>
                <?php if(count($topic) < 1):?>
                <div class="block-content">没有相关内容</div>
                <?php endif?>
                <article>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination'=>$pagination,
                ]);?>
                </article>
            </section>
        </div>

        <div class="col-md-3 sidebar">
            <?= $this->render('@frontend/views/weight/user')?>
            <?= $this->render('@frontend/views/weight/hot-topic')?>
            <?= $this->render('@frontend/views/weight/hot-node')?>
            <?= $this->render('@frontend/views/weight/count')?>
        </div>

    </div>



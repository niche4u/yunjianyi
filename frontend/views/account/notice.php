<?php

$this->title = '通知';
$this->params['breadcrumbs'][] = $this->title;
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
            <?php foreach($model as $n):?>

                <?php $topicInfo = \common\models\Topic::Info($n->topic_id);?>
                <?php $fromUserInfo = \common\models\User::Info($n->from_user_id);?>
                <?php $toUserInfo = \common\models\User::Info($n->to_user_id);?>

                <?php if($n->type == 1):?>
                    <article class="notice">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tbody>
                            <tr>
                                <td width="39" align="left" valign="top">
                                    <a href="/member/<?= $fromUserInfo['username']?>"><img src="<?= $fromUserInfo['avatar24']?>" class="img-rounded" border="0"></a>
                                </td>
                                <td valign="middle">
                                    <small><a href="/member/<?= $fromUserInfo['username']?>"><strong><?= $fromUserInfo['username']?></strong></a> 在 <a href="/topic/<?= $n->topic_id?>"><?= $topicInfo['title']?></a> 里回复了你</span> &nbsp; <?= Yii::$app->formatter->asRelativeTime($n->created)?></small> <a href="/account/notice-delete/?id=<?= $n->id?>" class="node">删除</a>
                                    <div class="mt10"></div>
                                    <div class="notice-content"><?= $n->msg?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </article>
                <?php endif?>

                <?php if($n->type == 2):?>
                    <article class="notice">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tbody>
                            <tr>
                                <td width="39" align="left" valign="top">
                                    <a href="/member/<?= $fromUserInfo['username']?>"><img src="<?= $fromUserInfo['avatar24']?>" class="img-rounded" border="0"></a>
                                </td>
                                <td valign="middle">
                                    <small><a href="/member/<?= $fromUserInfo['username']?>"><strong><?= $fromUserInfo['username']?></strong></a> 在回复 <a href="/topic/<?= $n->topic_id?>"><?= $topicInfo['title']?></a> 时提到了你</span> &nbsp; <?= Yii::$app->formatter->asRelativeTime($n->created)?></small> <a href="/account/notice-delete/?id=<?= $n->id?>" class="node">删除</a>
                                    <div class="mt10"></div>
                                    <div class="notice-content"><?= $n->msg?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </article>
                <?php endif?>

                <?php if($n->type == 3):?>
                    <article class="notice">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tbody>
                            <tr>
                                <td width="39" align="left" valign="top">
                                    <a href="/member/<?= $fromUserInfo['username']?>"><img src="<?= $fromUserInfo['avatar24']?>" class="img-rounded" border="0"></a>
                                </td>
                                <td valign="middle">
                                    <small><a href="/member/<?= $fromUserInfo['username']?>"><strong><?= $fromUserInfo['username']?></strong></a> 收藏了你的建议 <a href="/topic/<?= $n->topic_id?>"><?= $topicInfo['title']?></a> </span> &nbsp; <?= Yii::$app->formatter->asRelativeTime($n->created)?></small> <a href="/account/notice-delete/?id=<?= $n->id?>" class="node">删除</a>
                                    <div class="mt10"></div>
                                    <div class="notice-content"><?= $n->msg?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </article>
                <?php endif?>

            <?php endforeach?>
            <article>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination'=>$pagination,
                ]);?>
            </article>
        </section>
    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
    </div>
</div>

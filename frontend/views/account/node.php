<?php
$this->title = '收藏节点的主题';
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

            <?php foreach($model as $t):?>
                <article class="item">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody><tr>
                            <td width="48" valign="top" align="center"><a href="/member/<?= $t->user->username?>"><img src="<?= $t->user->avatar48?>" class="img-rounded"></a></td>
                            <td width="10"></td>
                            <td width="auto" valign="middle">
                                <h2><a href="/topic/<?= $t->id?><?php if($t->reply > 0):?>#reply<?= $t->reply?><?php endif?>"><?= $t->title?></a></h2>
                                <small><a class="node" href="/node/<?= $t->node->enname?>"><?= $t->node->name?></a> &nbsp;•&nbsp; <strong><a href="/member/<?= $t->user->username?>"><?= $t->user->username?></a></strong>
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
            <article>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination'=>$pagination,
                ]);?>
            </article>
        </section>
    </div>

    <div class="col-md-3 sidebar hidden-xs hidden-sm">
        <?= $this->render('@frontend/views/weight/user')?>
        <?= $this->render('@frontend/views/weight/node')?>
    </div>
</div>

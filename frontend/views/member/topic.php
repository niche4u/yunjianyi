<?php
$this->title = '提的建议';
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => '/member/'.$user->username];
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
                            <td width="auto" valign="middle">
                                <h2><a href="/topic/<?= $t['id'];?><?php if($t['reply'] > 0):?>#reply<?= $t['reply']?><?php endif?>"><?= $t['title']?></a></h2>
                                <small>
                                    <a class="node" href="/node/<?= $t['enname']?>"><?= $t['name']?></a> &nbsp;•&nbsp;
                                    <?php if(!empty($t['last_reply_time'])):?><?= Yii::$app->formatter->asRelativeTime($t['last_reply_time'])?> &nbsp;•&nbsp; 最后回复来自 <strong><a href="/member/<?= $t['last_reply_user']?>"><?= $t['last_reply_user']?></a></strong>
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

            <article class="item">
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

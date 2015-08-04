<?php
$this->title = $node->name;
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
            <article class="item">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody><tr>
                        <?php if($this->context->agent == 'is_iphone' || $this->context->agent == 'is_android'):?>
                            <td width="48" valign="top" align="left"><img src="<?= $node->logo80?>" class="img-rounded"></td>
                        <?php else:?>
                            <td width="80" valign="top" align="left"><img src="<?= $node->logo80?>" class="img-rounded"></td>
                        <?php endif?>
                        <td width="10"></td>
                        <td width="auto" valign="right">
                            <h2>
                                <?= $node->name?>
                                <span class="pull-right text-right">
                                    <?php if(!Yii::$app->user->isGuest):?>
                                        <?php if(\common\models\Follow::findOne(['user_id' => Yii::$app->user->id, 'follow_id' => $node->id, 'type' => 2]) === null):?>
                                            <button type="button" class="btn btn-success btn-xs" name="setting-button" onclick="location.href = '/follow/do/2/<?= $node->id?>?next=/node/<?= $node->enname?>';">加入收藏</button>
                                        <?php else:?>
                                            <button type="button" class="btn btn-default btn-xs" name="setting-button" onclick="location.href = '/follow/undo/2/<?= $node->id?>?next=/node/<?= $node->enname?>';">取消收藏</button>
                                        <?php endif?>
                                    <?php endif?>
                                </span>
                            </h2>
                            <small><?= $node->desc?></small>
                            <?php if(!Yii::$app->user->isGuest):?>
                            <?php if($this->context->agent != 'is_iphone' && $this->context->agent != 'is_android'):?>
                            <div class="mt10"></div>
                            <button type="button" class="btn btn-default btn-xs" name="setting-button" onclick="location.href = '/create/<?= $node->enname?>';">提建议</button>
                            <?php endif?>
                            <?php endif?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </article>
            <?= $this->render('@frontend/views/weight/topic_list', ['topic' => $model])?>
            <article>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination'=>$pagination,
                ]);?>
            </article>
        </section>
    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
        <?= $this->render('@frontend/views/weight/node-link', ['node' => $node->id])?>
        <?= $this->render('@frontend/views/weight/related-node', ['node' => $node])?>
        <?= $this->render('@frontend/views/weight/node-ad', ['node' => $node->id])?>
    </div>
</div>

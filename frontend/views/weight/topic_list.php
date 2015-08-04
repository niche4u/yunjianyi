<?php foreach($topic as $t):?>
    <article class="item">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tbody><tr>
                <?php if($this->context->agent == 'is_iphone' || $this->context->agent == 'is_android'):?>
                    <td width="24" valign="middle" align="center"><a href="/member/<?= $t['username']?>"><img src="<?= Yii::$app->params['avatarUrl'].'/24/'.$t['avatar']?>" class="img-rounded"></a></td>
                    <td width="10"></td>
                    <td width="auto" valign="middle">
                        <small>
                            <a class="node" href="/node/<?= $t['enname']?>"><?= $t['name']?></a> &nbsp;•&nbsp; <strong><a href="/member/<?= $t['username']?>"><?= $t['username']?></a></strong>
                        </small>
                        <div class="mt5"></div>
                        <h2><a href="/topic/<?= $t['id']?><?php if($t['reply'] > 0):?>#reply<?= $t['reply']?><?php endif?>"><?= $t['title']?></a></h2>
                        <small>
                            <?php if(!empty($t['last_reply_time'])):?><?= Yii::$app->formatter->asRelativeTime($t['last_reply_time'])?> &nbsp;•&nbsp; 最后回复 <strong><a href="/member/<?= $t['last_reply_user']?>"><?= $t['last_reply_user']?></a></strong>
                            <?php else:?>
                                <?= Yii::$app->formatter->asRelativeTime($t['updated_at'])?>
                            <?php endif?>
                        </small>
                    </td>
                <?php else:?>
                    <td width="48" valign="middle" align="center"><a href="/member/<?= $t['username']?>"><img src="<?= Yii::$app->params['avatarUrl'].'/48/'.$t['avatar']?>" class="img-rounded"></a></td>
                    <td width="10"></td>
                    <td width="auto" valign="middle">
                        <h2><a href="/topic/<?= $t['id']?><?php if($t['reply'] > 0):?>#reply<?= $t['reply']?><?php endif?>"><?= $t['title']?></a></h2>
                        <small><a class="node" href="/node/<?= $t['enname']?>"><?= $t['name']?></a> &nbsp;•&nbsp; <strong><a href="/member/<?= $t['username']?>"><?= $t['username']?></a></strong>
                            <?php if(!empty($t['last_reply_time'])):?>&nbsp;•&nbsp; <?= Yii::$app->formatter->asRelativeTime($t['last_reply_time'])?> &nbsp;•&nbsp; 最后回复 <strong><a href="/member/<?= $t['last_reply_user']?>"><?= $t['last_reply_user']?></a></strong>
                            <?php else:?>
                                &nbsp;•&nbsp; <?= Yii::$app->formatter->asRelativeTime($t['updated_at'])?>
                            <?php endif?>
                        </small>
                    </td>
                <?php endif?>
                <td width="50" align="right" valign="middle">
                    <?php if($t['reply'] > 0):?><a href="/topic/<?= $t['id']?>#reply<?= $t['reply']?>" class="badge"><?= $t['reply']?></a><?php endif?>
                </td>
            </tr>
            </tbody>
        </table>
    </article>
<?php endforeach?>
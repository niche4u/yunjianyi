<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->email_status == 0):?>
<div class="alert-warning alert fade in">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

    您的邮箱 <strong><?= Yii::$app->user->identity->email?></strong> 还未验证，暂时不能发帖，不能回复。请尽快登录邮箱查收邮件并激活。如果您未收到邮件，<a class="alert-link" href="/account/request-email-verify">点此重新发送</a>。

</div>
<?php endif?>
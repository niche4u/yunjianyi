<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->email_status == 0):?>
<div class="alert-warning alert fade in">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

    您的邮箱 <strong><?= Yii::$app->user->identity->email?></strong> 还未验证，暂时不能提建议，不能回复。请 <a class="alert-link" href="/account/request-email-verify">点此发送</a> 帐号激活邮件。

</div>
<?php endif?>
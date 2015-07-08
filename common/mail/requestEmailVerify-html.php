<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/email-verify', 'token' => $user->email_verify_token]);
?>
<div class="password-reset">
    <p>你好 <?= Html::encode($user->username) ?>,</p>

    <p>按照下面的链接激活您的邮箱:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>

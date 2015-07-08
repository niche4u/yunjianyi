<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/email-verify', 'token' => $user->email_verify_token]);
?>
你好 <?= $user->username ?>,

按照下面的链接激活您的邮箱:

<?= $resetLink ?>

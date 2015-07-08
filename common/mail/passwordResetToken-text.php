<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);
?>
你好 <?= $user->username ?>,

按照下面的链接重置您的密码:

<?= $resetLink ?>

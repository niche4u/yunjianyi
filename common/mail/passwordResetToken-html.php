<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>你好 <?= Html::encode($user->username) ?>,</p>

    <p>按照下面的链接重置您的密码:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>

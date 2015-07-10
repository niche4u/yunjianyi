<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \frontend\widgets\Alert::widget() ?>
<div class="row">
    <div class="col-lg-9">
        <section>
            <?= yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <article class="site-signup">

                <div class="row">
                    <div class="col-lg-9">
                        <?php $form = ActiveForm::begin(['id' => 'login-form', 'layout' => 'horizontal',
                            'fieldConfig' => [
                                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                'horizontalCssClasses' => [
                                    'label' => 'col-sm-4',
                                    'offset' => 'col-sm-offset-4',
                                    'wrapper' => 'col-sm-8',
                                    'error' => '',
                                    'hint' => '',
                                ],
                            ]]); ?>
                        <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'rememberMe')->checkbox()->label('记住登录') ?>
                        <p style="color:#999" class="col-sm-offset-4 col-sm-9">
                            如果您忘记密码，请<?= Html::a('找回密码', ['account/request-password-reset']) ?>.
                        </p>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-9">
                            <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </article>
<!--            <div class="topic-footer">-->
<!--                <a href="/site/auth?authclient=qq">QQ登录</a>&nbsp;&nbsp;-->
<!--                <a href="/site/auth?authclient=sina">微博登录</a>&nbsp;&nbsp;-->
<!--                <a href="/site/auth?authclient=weixin">微信登录</a>-->
<!--            </div>-->
        </section>
    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
    </div>

</div>
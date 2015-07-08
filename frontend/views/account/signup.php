<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = '注册';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-9">
        <section>
            <?= yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <article class="site-signup">

                <div class="row">
                    <div class="col-lg-9">
                        <?php $form = ActiveForm::begin(['id' => 'form-signup', 'layout' => 'horizontal',
                            'fieldConfig' => [
                                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                'horizontalCssClasses' => [
                                    'label' => 'col-sm-4',
                                    'offset' => 'col-sm-offset-4',
                                    'wrapper' => 'col-sm-8',
                                    'error' => '',
                                    'hint' => '',
                                ],
                            ],]); ?>
                            <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
                            <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
                            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                'captchaAction' => '/account/captcha',
                                'imageOptions'=>['alt'=>'点击换图','title'=>'点击换图', 'style'=>'cursor:pointer'],
                                'template' => '<div class="row"><div class="col-lg-8">{input}</div><div class="col-lg-4">{image}</div></div>',
                            ]) ?>
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-9">
                                <?= Html::submitButton('注册', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                                </div>
                            </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </article>
        </section>
    </div>

    <div class="col-md-3 sidebar hidden-xs hidden-sm">
        <?= $this->render('@frontend/views/weight/user')?>
    </div>

</div>
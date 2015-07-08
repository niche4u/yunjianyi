<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = '找回密码';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-9">
        <section>
            <?= yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>

            <article class="site-request-password-reset">
                <div class="row">
                    <div class="col-lg-7">
                        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'layout' => 'horizontal',
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
                        <?= $form->field($model, 'email') ?>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-9">
                            <?= Html::submitButton('发送', ['class' => 'btn btn-primary']) ?>
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
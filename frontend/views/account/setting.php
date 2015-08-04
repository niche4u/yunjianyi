<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '设置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-9">
        <section id="setting">
            <?= yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <article class="header">
                <?php if(Yii::$app->session['setting'] === 1) echo \frontend\widgets\Alert::widget() ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin(['layout' => 'horizontal',
                            'fieldConfig' => [
                                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                'horizontalCssClasses' => [
                                    'label' => 'col-sm-3',
                                    'offset' => 'col-sm-offset-3',
                                    'wrapper' => 'col-sm-7',
                                    'error' => '',
                                    'hint' => '',
                                ],
                            ]]); ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">用户名</label>
                            <div class="col-sm-7">
                                <p class="form-control-static"><?= $model->username?></p>
                            </div>
                        </div>
                        <?= $form->field($model, 'email')->textInput(['placeholder' => '邮箱', 'maxlength' => 255]) ?>
                        <?= $form->field($model, 'desc')->textarea(['rows' => 5, 'maxlength' => 100]) ?>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <?= Html::submitButton('保存设置', ['class' => 'btn btn-primary', 'name' => 'setting-button']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </article>
        </section>

        <section class="mt20" id="avatar">
            <div class="block-header"><small>设置头像</small></div>
            <div class="block-content">
                <?php if(Yii::$app->session['avatar'] === 1) echo \frontend\widgets\Alert::widget() ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin(['layout' => 'horizontal',
                            'options' => ['enctype' => 'multipart/form-data'],
                            'fieldConfig' => [
                                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                'horizontalCssClasses' => [
                                    'label' => 'col-sm-3',
                                    'offset' => 'col-sm-offset-3',
                                    'wrapper' => 'col-sm-7',
                                    'error' => '',
                                    'hint' => '',
                                ],
                            ]]); ?>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <?= Html::img($model->avatar80, ['class' => 'img-rounded'])?>
                                &nbsp;
                                <?= Html::img($model->avatar48, ['class' => 'img-rounded'])?>
                                &nbsp;
                                <?= Html::img($model->avatar24, ['class' => 'img-rounded'])?>
                            </div>
                        </div>
                        <?= $form->field($avatar, 'avatar')->fileInput() ?>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <?= Html::submitButton('保存头像', ['class' => 'btn btn-primary', 'name' => 'avatar-button']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt20" id="password">
            <div class="block-header"><small>修改密码</small></div>
            <div class="block-content">
                <?php if(Yii::$app->session['password'] === 1) echo \frontend\widgets\Alert::widget() ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin(['layout' => 'horizontal',
                            'fieldConfig' => [
                                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                'horizontalCssClasses' => [
                                    'label' => 'col-sm-3',
                                    'offset' => 'col-sm-offset-3',
                                    'wrapper' => 'col-sm-7',
                                    'error' => '',
                                    'hint' => '',
                                ],
                            ]]); ?>
                        <?= $form->field($password, 'oldpassword')->passwordInput() ?>
                        <?= $form->field($password, 'password')->passwordInput() ?>
                        <?= $form->field($password, 'repassword')->passwordInput() ?>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <?= Html::submitButton('修改密码', ['class' => 'btn btn-primary', 'name' => 'password-button']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="col-md-3 sidebar">
        <?= $this->render('@frontend/views/weight/user')?>
    </div>

</div>

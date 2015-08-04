<?php
namespace frontend\controllers;

use common\models\Follow;
use common\models\Node;
use common\models\Notice;
use common\models\Topic;
use common\models\User;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\AvatarForm;
use frontend\models\EditPasswordForm;
use yii\web\NotFoundHttpException;

/**
 * Account controller
 */
class AccountController extends FrontendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'height' => 35,
                'width' => 125,
                'offset' => 3,
            ],
        ];
    }

    public function actionLogin()
    {
        $this->title = '用户登陆'.' - '.Yii::$app->name;
        $this->description = '';
        $this->canonical = Yii::$app->params['domain'].'login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $next = Yii::$app->request->get('next');
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(isset($next)) return $this->redirect($next);
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $this->title = '用户注册'.' - '.Yii::$app->name;
        $this->description = '';
        $this->canonical = Yii::$app->params['domain'].'signup';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->cache->delete('UserCount');
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $this->title = '找回密码'.' - '.Yii::$app->name;
        $this->description = '';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', '邮件发送成功！请检查您的电子邮箱以获得进一步操作说明。');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', '很抱歉，我们无法提供密码重置邮件。');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionRequestEmailVerify()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = User::findOne(Yii::$app->user->id);
        $user->generateEmailVerifyToken();
        if($user->save()) {
            $rst = \Yii::$app->mailer->compose(['html' => 'requestEmailVerify-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setTo($user->email)
                ->setSubject(\Yii::$app->name.' 邮箱激活')
                ->send();
            if($rst) {
                Yii::$app->getSession()->setFlash('success', '邮件发送成功！请检查您的电子邮箱以获得进一步操作说明。');
                return $this->goHome();
            }
        }
    }

    public function actionEmailVerify()
    {
        $token = Yii::$app->request->get('token');
        $user = User::findOne(['email_verify_token' => $token]);
        if(!$user) throw new InvalidParamException('Wrong email verify token.');

        $user->email_status = 1;
        $user->removeEmailVerifyToken();
        if($user->save()) {
            Yii::$app->getSession()->setFlash('success', "<strong>".$user->email."</strong>".' 邮件激活成功！');
            return $this->goHome();
        }

    }

    public function actionResetPassword($token)
    {
        $this->title = '重置密码'.' - '.Yii::$app->name;
        $this->description = '';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', '重置密码成功。');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    /**
     * @return array|string
     */
    public function actionSetting()
    {
        $this->title = '设置'.' - '.Yii::$app->name;
        $this->description = '';

        if (Yii::$app->user->isGuest) {
            return $this->redirect('/account/login?next=/account/setting');
        }

        $model = User::findOne(Yii::$app->user->id);
        $avatar = new AvatarForm();
        $password = new EditPasswordForm();

        if(Yii::$app->request->post() != null) {
            if($model->load(Yii::$app->request->post()))
            {
                $model->updated_at = time();
                if($model->update()) {
                    Yii::$app->getSession()->setFlash('setting', 1);
                    Yii::$app->getSession()->setFlash('success', '修改成功');
                    Yii::$app->cache->delete('user'.Yii::$app->user->id);
                    return $this->refresh();
                }
                else {
                    Yii::$app->getSession()->setFlash('setting', 1);
                    Yii::$app->getSession()->setFlash('danger', '修改失败');
                }
            }

            if($avatar->load(Yii::$app->request->post()))
            {
                if($avatar->update()) {
                    Yii::$app->cache->delete('user'.Yii::$app->user->id);
                    Yii::$app->getSession()->setFlash('avatar', 1);
                    Yii::$app->getSession()->setFlash('success', '修改成功');
                    return $this->redirect('/account/setting#avatar');
                }
                else {
                    Yii::$app->getSession()->setFlash('avatar', 1);
                    Yii::$app->getSession()->setFlash('danger', '修改失败');
                }
            }

            if($password->load(Yii::$app->request->post()))
            {
                if($password->update()) {
                    Yii::$app->getSession()->setFlash('password', 1);
                    Yii::$app->getSession()->setFlash('success', '修改成功');
                    return $this->redirect('/account/setting#password');
                }
                else {
                    Yii::$app->getSession()->setFlash('password', 1);
                    Yii::$app->getSession()->setFlash('danger', '修改失败');
                }
            }

        }

        return $this->render('setting', ['model' => $model, 'avatar' => $avatar, 'password' => $password]);
    }

    public function actionNotice()
    {
        $this->title = '通知'.' - '.Yii::$app->name;
        $this->description = '';

        if (Yii::$app->user->isGuest) {
            return $this->redirect('/account/login?next=/account/notice');
        }
        Notice::updateAll(['is_read' => 1], 'is_read = 0 and to_user_id = '.Yii::$app->user->id);
        Yii::$app->cache->delete('NoticeCount'.Yii::$app->user->id);
        $query = Notice::find()->where(['to_user_id' => Yii::$app->user->id]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $model = $query->orderBy(['id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('notice', ['model' => $model, 'pagination'=> $pagination]);
    }

    public function actionNoticeDelete($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/account/login?next=/account/notice-delete?id='.$id);
        }
        if (empty($id)) {
            return $this->goHome();
        }
        $notice = Notice::findOne($id);
        if (!empty($notice)) $notice->delete();
        return $this->redirect('/account/notice');
    }

    public function actionFollow()
    {
        $this->title = '关注的人提的建议'.' - '.Yii::$app->name;
        $this->description = '';

        if (Yii::$app->user->isGuest) {
            return $this->redirect('/account/login?next=/account/follow');
        }
        $query = (new Query())->select('topic.*, node.enname, node.name, user.username, user.avatar')
            ->from(Topic::tableName())
            ->leftJoin(Node::tableName(), 'node.id = topic.node_id')
            ->leftJoin(User::tableName(), 'user.id = topic.user_id')
            ->where(['in', 'topic.user_id', Follow::User()]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $model = $query->orderBy(['id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('follow', ['model' => $model, 'pagination'=> $pagination]);
    }

    public function actionNode()
    {
        $this->title = '收藏的节点的建议'.' - '.Yii::$app->name;
        $this->description = '';

        if (Yii::$app->user->isGuest) {
            return $this->redirect('/account/login?next=/account/node');
        }

        $query = (new Query())->select('topic.*, node.enname, node.name, user.username, user.avatar')
            ->from(Topic::tableName())
            ->leftJoin(Node::tableName(), 'node.id = topic.node_id')
            ->leftJoin(User::tableName(), 'user.id = topic.user_id')
            ->where(['in', 'node.id', Follow::Node()]);

        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $model = $query->orderBy(['id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('node', ['model' => $model, 'pagination'=> $pagination]);
    }

    public function actionTopic()
    {
        $this->title = '关注的建议'.' - '.Yii::$app->name;
        $this->description = '';

        if (Yii::$app->user->isGuest) {
            return $this->redirect('/account/login?next=/account/topic');
        }
        $query = (new Query())->select('topic.*, node.enname, node.name, user.username, user.avatar')
            ->from(Topic::tableName())
            ->leftJoin(Node::tableName(), 'node.id = topic.node_id')
            ->leftJoin(User::tableName(), 'user.id = topic.user_id')
            ->where(['in', 'topic.id', Follow::Topic()]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $model = $query->orderBy(['id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('topic', ['model' => $model, 'pagination'=> $pagination]);
    }
}

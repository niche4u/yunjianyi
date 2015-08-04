<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\Html;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_verify_token
 * @property string $avatar
 * @property integer $role
 * @property integer $status
 * @property integer $email_status
 * @property string $desc
 * @property integer $updated_at
 * @property integer $created_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;

    //public $avatar;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'updated_at', 'created_at'], 'required'],
            [['role', 'status', 'email_status', 'updated_at', 'created_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'email_verify_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['avatar', 'desc'], 'string', 'max' => 100],
            [['avatar'], 'string', 'max' => 30],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['email'], 'unique'],
            ['role', 'default', 'value' => 10],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    public function getIsAdmin()
    {
        return $this->role == static::ROLE_ADMIN;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new email verify token
     */
    public function generateEmailVerifyToken()
    {
        $this->email_verify_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes email verify token
     */
    public function removeEmailVerifyToken()
    {
        $this->email_verify_token = null;
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'email_verify_token' => '邮箱激活token',
            'avatar' => '头像',
            'role' => '角色',
            'status' => '状态',
            'email_status' => '邮箱激活',
            'desc' => '个人介绍',
            'updated_at' => '最后更新',
            'created_at' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopicList()
    {
        return (new Query())->select('topic.*, node.enname, node.name, user.username, user.avatar')
            ->from(Topic::tableName().' topic')
            ->leftJoin(Node::tableName().' node', 'node.id = topic.node_id')
            ->leftJoin(User::tableName().' user', 'user.id = topic.user_id')
            ->where(['node.is_hidden' => 0])
            ->andWhere(['topic.user_id' => $this->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(10)
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplyList()
    {
        return (new Query())->select('reply.*, topic.title, node.enname, node.name, user.username, user.avatar')
            ->from(Reply::tableName())
            ->leftJoin(Topic::tableName(), 'topic.id = reply.topic_id')
            ->leftJoin(Node::tableName(), 'node.id = topic.node_id')
            ->leftJoin(User::tableName().' user', 'user.id = reply.user_id')
            ->where(['node.is_hidden' => 0])
            ->andWhere(['reply.user_id' => $this->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(10)
            ->all();
    }

    public function getAvatar80()
    {
        return Yii::$app->params['avatarUrl'].'/80/'.$this->avatar;
    }

    public function getAvatar48()
    {
        return Yii::$app->params['avatarUrl'].'/48/'.$this->avatar;
    }

    public function getAvatar24()
    {
        return Yii::$app->params['avatarUrl'].'/24/'.$this->avatar;
    }

    public function getAvatar80Show()
    {
        return Html::img(Yii::$app->params['avatarUrl'].'/80/'.$this->avatar);
    }

    public function getAvatar48Show()
    {
        return Html::img(Yii::$app->params['avatarUrl'].'/48/'.$this->avatar);
    }

    public function getAvatar24Show()
    {
        return Html::img(Yii::$app->params['avatarUrl'].'/24/'.$this->avatar);
    }

    //获取用户信息，有缓存就获取缓存
    static function Info($id)
    {
        if(!$userInfo = Yii::$app->cache->get('user'.$id))
        {
            $userInfo = User::find()->where(['id' => $id])->select(['id', 'username', 'email', 'avatar', 'role'])->asArray()->one();
            $userInfo['avatar100'] = Yii::$app->params['avatarUrl'].'/80/'.$userInfo['avatar'];
            $userInfo['avatar48'] = Yii::$app->params['avatarUrl'].'/48/'.$userInfo['avatar'];
            $userInfo['avatar24'] = Yii::$app->params['avatarUrl'].'/24/'.$userInfo['avatar'];
            Yii::$app->cache->set('user'.$id, $userInfo, 0);
        }
        return $userInfo;
    }

    static function UserCount()
    {
        if(!$UserCount = Yii::$app->cache->get('UserCount'))
        {
            $UserCount = User::find()->count();
            Yii::$app->cache->set('UserCount', $UserCount, 86400);
        }
        return $UserCount;
    }

    /**
     * @inheritdoc
     */
    public function getStatusLabel()
    {
        $statuses = self::getArrayStatus();
        return $statuses[$this->status];
    }

    /**
     * @inheritdoc
     */
    public static function getArrayStatus()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_DELETED => Yii::t('app', 'STATUS_DELETED'),
        ];
    }

    public function getArrayRole()
    {
        return [
            '0' => '禁用',
            '10' => '普通用户',
            '20' => '管理员',
        ];
    }

    public function getRoleName()
    {
        $roles = $this->arrayRole;
        return $roles[$this->role];
    }

}

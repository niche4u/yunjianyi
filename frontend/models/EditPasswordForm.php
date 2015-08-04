<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * EditPassword  form
 */
class EditPasswordForm extends Model
{
    /**
     * @var string $password Password
     */
    public $password;
    /**
     * @var string $repassword Repeat password
     */
    public $repassword;
    /**
     * @var string Current password
     */
    public $oldpassword;
    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldpassword', 'password', 'repassword'], 'trim'],
            [['oldpassword', 'password', 'repassword'], 'required'],
            [['oldpassword', 'password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            [['oldpassword'], 'validateOldPassword']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oldpassword' => '旧密码',
            'password' => '新密码',
            'repassword' => '重复新密码',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validateOldPassword($attribute, $params)
    {
        $user = $this->user;
        if (!$user || !$user->validatePassword($this->$attribute)) {
            $this->addError($attribute, '当前密码输入错误');
        }
    }

    /**
     * update user.
     *
     * @return boolean true if  update was successfully changed
     */
    public function update()
    {
        if ($this->validate()) {
            if(!empty($this->password)) {
                $this->user->setPassword($this->password);
                $this->user->removePasswordResetToken();
            }
            $this->user->updated_at = time();
            return $this->user->save();
        }
        return false;
    }

    /**
     * Finds user by id.
     *
     * @return User|null User instance
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        }
        return $this->_user;
    }

}

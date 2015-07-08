<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Avatar  form
 */
class AvatarForm extends Model
{
    public $avatar;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['avatar', 'file', 'extensions' => ['png', 'jpeg', 'jpg', 'gif'], 'maxSize' => 1024 * 1024 * 2, 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'avatar' => '头像',
        ];
    }

    /**
     * update user.
     *
     * @return boolean true if  update was successfully changed
     */
    public function update()
    {
        if ($this->validate()) {
            $this->avatar = UploadedFile::getInstance($this, 'avatar');
            if ($this->avatar != null && $this->validate()) {
                $filename = date('Ymdhis') . rand(1000, 9999);
                $this->avatar->saveAs(Yii::getAlias('@avatar').'/origin/' . $filename . '.' . $this->avatar->extension);
                $extension = $this->avatar->extension;
                $this->avatar = $filename . '.' . $extension;
                Image::thumbnail(Yii::getAlias('@avatar').'/origin/' . $filename . '.' . $extension, 80, 80)->save(Yii::getAlias('@avatar').'/80/' . $filename . '.' . $extension, ['quality' => 80]);
                Image::thumbnail(Yii::getAlias('@avatar').'/origin/' . $filename . '.' . $extension, 48, 48)->save(Yii::getAlias('@avatar').'/48/' . $filename . '.' . $extension, ['quality' => 80]);
                Image::thumbnail(Yii::getAlias('@avatar').'/origin/' . $filename . '.' . $extension, 24, 24)->save(Yii::getAlias('@avatar').'/24/' . $filename . '.' . $extension, ['quality' => 80]);
            }
            if (!empty($this->avatar)) {

                if(!empty($this->user->avatar) && $this->user->avatar != 'default.png') {
                    if(file_exists(Yii::getAlias('@avatar').'/origin/'.$this->user->avatar)) unlink(Yii::getAlias('@avatar').'/origin/'.$this->user->avatar);
                    if(file_exists(Yii::getAlias('@avatar').'/80/'.$this->user->avatar)) unlink(Yii::getAlias('@avatar').'/80/'.$this->user->avatar);
                    if(file_exists(Yii::getAlias('@avatar').'/48/'.$this->user->avatar)) unlink(Yii::getAlias('@avatar').'/48/'.$this->user->avatar);
                    if(file_exists(Yii::getAlias('@avatar').'/24/'.$this->user->avatar)) unlink(Yii::getAlias('@avatar').'/24/'.$this->user->avatar);
                }

                $this->user->avatar = $this->avatar;
                $this->user->updated_at = time();
                return $this->user->save();
            }
            return true;
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

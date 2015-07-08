<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "follow".
 *
 * @property integer $user_id
 * @property integer $follow_id
 */
class Follow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'follow';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'follow_id'], 'required'],
            [['user_id', 'follow_id', 'type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用户',
            'follow_id' => '关注了',
            'type' => '1用户;2节点;3主题',
        ];
    }

    /**
     * 获取关注的人用户信息
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'follow_id']);
    }

    /**
     * 获取关注的节点信息
     * @return \yii\db\ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(Node::className(), ['id' => 'follow_id']);
    }

    /**
     * 获取关注的主题信息
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'follow_id']);
    }
}
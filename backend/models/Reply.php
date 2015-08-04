<?php

namespace backend\models;

use common\models\Topic;
use common\models\User;
use Yii;

/**
 * This is the model class for table "reply".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $topic_id
 * @property string $content
 * @property integer $created
 */
class Reply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['topic_id', 'content'], 'required'],
            [['topic_id', 'created'], 'integer'],
            ['user_id', 'default', 'value' => Yii::$app->user->id],
            ['created', 'default', 'value' => time()],
            [['content'], 'string', 'max' => 20000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '回复ID',
            'user_id' => '回复者',
            'topic_id' => '建议',
            'content' => '回复内容',
            'created' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id']);
    }
}
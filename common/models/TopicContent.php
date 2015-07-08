<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "topic_content".
 *
 * @property integer $id
 * @property integer $topic_id
 * @property string $content
 * @property integer $is_append
 * @property integer $created
 *
 * @property Topic $topic
 */
class TopicContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topic_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['topic_id', 'created'], 'required'],
            [['topic_id', 'is_append', 'created'], 'integer'],
            [['content'], 'string', 'max' => 20000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'topic_id' => '主题ID',
            'content' => '主题正文',
            'is_append' => '是否追加',
            'created' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id']);
    }

    /**
     * @param boolean $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(empty($this->content)) $this->content = '';
        return parent::beforeSave($insert);
    }
}
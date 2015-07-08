<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property string $key
 * @property string $value
 * @property string $desc
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value', 'desc'], 'required'],
            [['key'], 'string', 'max' => 30],
            [['value'], 'string', 'max' => 300],
            [['desc'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => '变量',
            'value' => '值',
            'desc' => '描述',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tab_nav".
 *
 * @property integer $tab_id
 * @property string $name
 * @property string $link
 * @property integer $sort
 */
class TabNav extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tab_nav';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tab_id', 'name', 'link', 'sort'], 'required'],
            [['tab_id', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 25],
            [['link'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tab_id' => 'tab id',
            'name' => '名称',
            'link' => '链接',
            'sort' => '排序',
        ];
    }
}
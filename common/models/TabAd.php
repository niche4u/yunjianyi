<?php

namespace common\models;

use common\components\Helper;
use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;

/**
 * This is the model class for table "tab_ad".
 *
 * @property integer $id
 * @property integer $tab_id
 * @property string $content
 * @property integer $status
 */
class TabAd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tab_ad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tab_id', 'content'], 'required'],
            [['tab_id', 'status'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'tab_id' => 'tab',
            'content' => '广告内容',
            'status' => '启用',
        ];
    }

    /**
     * 获取所在tab
     * @return \yii\db\ActiveQuery
     */
    public function getTab()
    {
        return $this->hasOne(Tab::className(), ['id' => 'tab_id']);
    }


    //获取Tab广告信息，有缓存就获取缓存
    static function TabAd($tab)
    {
        $TabAd = Yii::$app->cache->get('TabAd'.$tab);
        if(!isset($TabAd))
        {
            $TabModel = Tab::findOne(['enname' => $tab]);
            if(!empty($TabModel->id)) {
                $TabAd = TabAd::find()->where(['tab_id' => $TabModel->id])->asArray()->all();
                Yii::$app->cache->set('TabAd'.$tab, $TabAd, 0);
            }
            else {
                return null;
            }
        }
        return $TabAd;
    }

    public function afterFind()
    {
        $this->content  = Helper::autoLink(HtmlPurifier::process(Markdown::process($this->content, 'gfm-comment')));
        parent::afterFind();
    }
}

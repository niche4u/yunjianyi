<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tab".
 *
 * @property integer $id
 * @property string $name
 * @property string $enname
 * @property string $bg
 * @property string $bg_color
 * @property integer $use_bg
 * @property integer $sort
 * @property integer $status
 *
 * @property Node[] $nodes
 */
class Tab extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tab';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'enname'], 'required'],
            [['use_bg', 'sort', 'status'], 'integer'],
            [['name', 'enname', 'bg_color'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'tab id',
            'name' => 'tab名称',
            'enname' => '英文名称',
            'bg' => '背景图',
            'bg_color' => '背景颜色',
            'use_bg' => '启用背景图',
            'sort' => '排序',
            'status' => '状态',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodes()
    {
        return $this->hasMany(Node::className(), ['tab_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
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

    /**
     * @inheritdoc
     */
    public function getStatusLabel()
    {
        $statuses = self::getArrayStatus();
        return $statuses[$this->status];
    }

    /**
     * tab下面的右边的操作tab
     * @return \yii\db\ActiveQuery
     */
    public function getTabRight()
    {
        return $this->hasMany(TabNav::className(), ['tab_id' => 'id']);
    }

    /**
     * tab,首页显示的主导航菜单
     * 获取tab
     * @return array|\yii\db\ActiveRecord[]
     */
    static function Tab()
    {
        if(!$tab = Yii::$app->cache->get('tab'))
        {
            $tab = Tab::find()->where(['status' => 1])->orderBy(['sort' => SORT_ASC])->all();
            Yii::$app->cache->set('tab', $tab, 0);
        }
        return $tab;
    }

    //获取tab信息，有缓存就获取缓存
    static function Info($enname)
    {
        if(!$TabInfo = Yii::$app->cache->get('TabInfo'.$enname))
        {
            $TabInfo = Tab::find()->where(['enname' => $enname])->asArray()->one();
            Yii::$app->cache->set('TabInfo'.$enname, $TabInfo, 0);
        }
        return $TabInfo;
    }

    /**
     * 获取tab的节点id，有缓存就获取缓存，用来取tab下面的节点建议
     * @return array|\yii\db\ActiveRecord[]
     */
    static function SubNodeId($enname)
    {
        if(!$SubNodeId = Yii::$app->cache->get('subnodeid'.$enname))
        {
            $tab = Tab::findOne(['enname' => $enname]);
            if(empty($tab->id)) return [];
            $SubNodeId = ArrayHelper::map(Node::find()->where(['tab_id' => $tab->id])->andWhere(['is_hidden' => 0])->all(), 'id', 'id');
            Yii::$app->cache->set('subnodeid'.$enname, $SubNodeId, 0);
        }
        return $SubNodeId;
    }

    /**
     * 获取节点的子节点，有缓存就获取缓存，用于主tab下面的子tab显示
     * @return array|\yii\db\ActiveRecord[]
     */
    static function SubTab($enname)
    {
        if(!$SubTab = Yii::$app->cache->get('SubTab'.$enname))
        {
            $SubTab = Tab::find()->where(['enname' => $enname])->with('tabRight')->with('nodes')->asArray()->one();
            Yii::$app->cache->set('SubTab'.$enname, $SubTab, 0);
        }
        return $SubTab;
    }


    public function edit()
    {
        if ($this->validate()) {
            $this->bg = UploadedFile::getInstance($this, 'bg');
            if ($this->bg != null && $this->validate()) {
                $filename = date('Ymdhis') . rand(1000, 9999);
                $this->bg->saveAs(Yii::getAlias('@bg').'/' . $filename . '.' . $this->bg->extension);
                $extension = $this->bg->extension;
                $this->bg = $filename . '.' . $extension;
            }
            if (!empty($this->bg)) {
                if(!empty($this->oldAttributes['bg'])) {
                    if(file_exists(Yii::getAlias('@bg').'/'.$this->oldAttributes['bg'])) unlink(Yii::getAlias('@bg').'/'.$this->oldAttributes['bg']);
                }
            }
            else {
                $this->bg = $this->oldAttributes['bg'];
            }

            return $this->save();
        }
        return false;
    }


    public function create()
    {
        if ($this->validate()) {
            $this->bg = UploadedFile::getInstance($this, 'bg');
            if ($this->bg != null) {
                $filename = date('Ymdhis') . rand(1000, 9999);
                $this->bg->saveAs(Yii::getAlias('@bg').'/' . $filename . '.' . $this->bg->extension);
                $extension = $this->bg->extension;
                $this->bg = $filename . '.' . $extension;
            }

            return $this->save();
        }
        return false;
    }
}
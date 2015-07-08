<?php

namespace xj\sitemap\models;

use yii\base\Model;

class Url extends Model {

    const CHANGEFREQ_ALWAYS = 'always';
    const CHANGEFREQ_HOURLY = 'hourly';
    const CHANGEFREQ_DAILY = 'daily';
    const CHANGEFREQ_WEEKLY = 'weekly';
    const CHANGEFREQ_MONTHLY = 'monthly';
    const CHANGEFREQ_YEARLY = 'yearly';
    const CHANGEFREQ_NEVER = 'never';

    public $loc;
    public $lastmod;
    public $changefreq;
    public $priority;

    public function rules() {
        return [
            ['loc', 'required'],
            ['lastmod', 'string'],
            ['changefreq', 'in', 'range' => array_keys($this->getChangefreqOptions())],
            ['priority', 'number', 'min' => 0.1, 'max' => 1],
        ];
    }

    public function validDatetime() {
//        YYYY-MM-DDThh:mmTZD
    }

    public function attributeLabels() {
        return [
            'loc' => 'Loc',
            'lastmod' => 'LastMod',
            'changefreq' => 'ChangeFreq',
            'priority' => 'Priority',
        ];
    }

    /**
     * Change Freq Options
     * @return []
     */
    public function getChangefreqOptions() {
        return [
            self::CHANGEFREQ_ALWAYS => 'Always',
            self::CHANGEFREQ_HOURLY => 'Hourly',
            self::CHANGEFREQ_DAILY => 'Daily',
            self::CHANGEFREQ_WEEKLY => 'Weekly',
            self::CHANGEFREQ_MONTHLY => 'Monthly',
            self::CHANGEFREQ_YEARLY => 'Yearly',
            self::CHANGEFREQ_NEVER => 'Never',
        ];
    }

    /**
     * Change Freq Text
     * @return string
     */
    public function getChangeFreqText() {
        $options = $this->getChangefreqOptions();
        return isset($options[$this->changefreq]) ? $options[$this->changefreq] : $this->changefreq;
    }
    
    /**
     * Create Sitemap
     * @param string $loc
     * @param string $lastmod
     * @param string $changeFreq
     * @param int $priority
     * @return Url
     */
    public static function create($loc, $lastmod, $changeFreq, $priority) {
        $model = new static();
        $model->attributes = [
            'loc' => $loc,
            'lastmod' => $lastmod,
            'changefreq' => $changeFreq,
            'priority' => $priority,
        ];
        return $model;
    }
}

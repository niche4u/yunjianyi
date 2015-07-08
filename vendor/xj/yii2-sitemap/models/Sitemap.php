<?php

namespace xj\sitemap\models;

use yii\base\Model;

class Sitemap extends Model {

    public $loc;
    public $lastmod;

    public function rules() {
        return [
            ['loc', 'required'],
            ['lastmod', 'string'],
        ];
    }

    public function validDatetime() {
//        YYYY-MM-DDThh:mmTZD
    }

    public function attributeLabels() {
        return [
            'loc' => 'Loc',
            'lastmod' => 'LastMod',
        ];
    }

    /**
     * Create Sitemap
     * @param string $loc
     * @param string $lastmod
     * @return Sitemap
     */
    public static function create($loc, $lastmod) {
        $model = new static();
        $model->attributes = [
            'loc' => $loc,
            'lastmod' => $lastmod,
        ];
        return $model;
    }

}

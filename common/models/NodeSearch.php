<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NodeSearch represents the model behind the search form about `common\models\Node`.
 */
class NodeSearch extends Node
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tab_id', 'parent_id', 'is_hidden', 'need_login', 'sort', 'created'], 'integer'],
            [['name', 'enname', 'desc', 'logo'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Node::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tab_id' => $this->tab_id,
            'parent_id' => $this->parent_id,
            'is_hidden' => $this->is_hidden,
            'need_login' => $this->need_login,
            'sort' => $this->sort,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'enname', $this->enname])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'logo', $this->logo]);

        return $dataProvider;
    }
}

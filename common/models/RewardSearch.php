<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Reward;

/**
 * RewardSearch represents the model behind the search form about `common\models\Reward`.
 */
class RewardSearch extends Reward
{
    /**
     * @inheritdoc
     */ 
    public function rules()
    {
        return [
            [['id', 'is_active', 'motivation_id', 'need_money', 'min_exp', 'min_level','created_by', 'updated_by'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params, $motivation_id)
    {
        $query = Reward::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_active' => $this->is_active,
            'motivation_id' => $this->motivation_id,
            'need_money' => $this->need_money,
            //'need_exp' => $this->need_exp,
            'min_exp' => $this->min_exp,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['motivation_id' => $motivation_id]);
        $query->andFilterWhere(['is_active' => 1]);

        return $dataProvider;
    }
}

<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Motivation;

/**
 * MotivationSearch represents the model behind the search form about `common\models\Motivation`.
 */
class MotivationSearch extends Motivation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'owner_id'], 'integer'],
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
    public function search($params, $author_id = null, $customParams = [])
    {
        $query = Motivation::find();

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
        
        if($author_id > 0) {
            $this->owner_id = $author_id;
        }

        //Если нужно просмотреть мотивации, в которых участвует пользователь, то нужно сджоинить таблицу activity и найти активити пользователя
        if(isset($customParams["inprogress_id"]) && $customParams["inprogress_id"] > 0) {
            $query->joinWith('activity')->andFilterWhere([
                'activity.user_id' => $customParams["inprogress_id"]
            ]);
        }

        // grid filtering conditions
        $query->With('owner')->andFilterWhere([
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['motivation.is_active' => true]);

        return $dataProvider;
    }
}

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StoreLog;

/**
 * StoreLogSearch represents the model behind the search form about `app\models\StoreLog`.
 */
class StoreLogSearch extends StoreLog
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'user_id', 'stock', 'price', 'date'], 'integer'],
			[['developer_payload', 'purchase_state', 'market', 'token'], 'safe'],
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
		$query = StoreLog::find()->orderBy('id DESC');
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => [
				'defaultOrder' => ['id' => SORT_DESC],
			],
		]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'      => $this->id,
			'user_id' => $this->user_id,
			'stock'   => $this->stock,
			'price'   => $this->price,
			'date'    => $this->date,
		]);
		
		$query->andFilterWhere(['like', 'developer_payload', $this->developer_payload])
			->andFilterWhere(['like', 'purchase_state', $this->purchase_state])
			->andFilterWhere(['like', 'market', $this->market])
			->andFilterWhere(['token', 'token', $this->token]);
		
		return $dataProvider;
	}
}

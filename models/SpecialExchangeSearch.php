<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SpecialExchange;

/**
 * SpecialExchangeSearch represents the model behind the search form about `app\models\SpecialExchange`.
 */
class SpecialExchangeSearch extends SpecialExchange
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'channel_id', 'from_group', 'target_group', 'paid_amount', 'paid_amount_back', 'date', 'status'], 'integer'],
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
		$query = SpecialExchange::find()->orderBy('id DESC');
		
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
			'id'               => $this->id,
			'channel_id'       => $this->channel_id,
			'from_group'       => $this->from_group,
			'target_group'     => $this->target_group,
			'paid_amount'      => $this->paid_amount,
			'paid_amount_back' => $this->paid_amount_back,
			'date'             => $this->date,
			'status'           => $this->status,
		]);
		
		return $dataProvider;
	}
}
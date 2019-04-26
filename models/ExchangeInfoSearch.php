<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExchangeInfo;

/**
 * ExchangeInfoSearch represents the model behind the search form about `app\models\ExchangeInfo`.
 */
class ExchangeInfoSearch extends ExchangeInfo
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'start_hour', 'finish_hour', 'date', 'same_channels_count', 'special_channels_count', 'first_request_date', 'last_request_date', 'total_coin_earned', 'total_coin_back', 'violated_channels_count', 'first_checking_date', 'last_checking_date'], 'integer'],
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
		$query = ExchangeInfo::find()->orderBy('id DESC');
		
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
			'start_hour' => $this->start_hour,
			'finish_hour' => $this->finish_hour,
			'date' => $this->date,
			'same_channels_count' => $this->same_channels_count,
			'special_channels_count' => $this->special_channels_count,
			'first_request_date' => $this->first_request_date,
			'last_request_date' => $this->last_request_date,
			'total_coin_earned' => $this->total_coin_earned,
			'total_coin_back' => $this->total_coin_back,
			'violated_channels_count' => $this->violated_channels_count,
			'first_checking_date' => $this->first_checking_date,
			'last_checking_date' => $this->last_checking_date,
		]);
		
		return $dataProvider;
	}
}
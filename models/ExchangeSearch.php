<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Exchange;

/**
 * ExchangeSearch represents the model behind the search form about `app\models\Exchange`.
 */
class ExchangeSearch extends Exchange
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'special_channel_id', 'special_exchange_id', 'channels_group', 'date', 'finish_date', 'status', 'info_id'], 'integer'],
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
		$query = Exchange::find()->orderBy('id DESC');
		
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
			'special_channel_id' => $this->special_channel_id,
			'special_exchange_id' => $this->special_exchange_id,
			'channels_group' => $this->channels_group,
			'date' => $this->date,
			'finish_date' => $this->finish_date,
			'status' => $this->status,
			'info_id' => $this->info_id,
		]);
		
		return $dataProvider;
	}
}
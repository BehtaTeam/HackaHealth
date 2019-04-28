<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Area;

/**
 * AreaSearch represents the model behind the search form about `app\models\Area`.
 */
class AreaSearch extends Area
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'pollute'], 'integer'],
			[['lat1', 'long1', 'lat2', 'long2', 'lat3', 'long3', 'lat4', 'long4'], 'number'],
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
		$query = Area::find();
		
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
			'id'      => $this->id,
			'lat1'    => $this->lat1,
			'long1'   => $this->long1,
			'lat2'    => $this->lat2,
			'long2'   => $this->long2,
			'lat3'    => $this->lat3,
			'long3'   => $this->long3,
			'lat4'    => $this->lat4,
			'long4'   => $this->long4,
			'pollute' => $this->pollute,
		]);
		
		return $dataProvider;
	}
}
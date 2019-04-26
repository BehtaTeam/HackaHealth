<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'telegram_id', 'register_date', 'last_activity_time', 'stock', 'spent', 'api_version', 'app_version', 'active', 'type'], 'integer'],
			[['username', 'first_name', 'last_name', 'phone', 'access_token', 'last_login_ip', 'device_id', 'serial_number', 'model', 'manufacture', 'brand', 'email', 'password'], 'safe'],
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
		$query = User::find();
		
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
			'telegram_id' => $this->telegram_id,
			'register_date' => $this->register_date,
			'last_activity_time' => $this->last_activity_time,
			'stock' => $this->stock,
			'spent' => $this->spent,
			'api_version' => $this->api_version,
			'app_version' => $this->app_version,
			'active' => $this->active,
			'type' => $this->type,
		]);
		
		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'first_name', $this->first_name])
			->andFilterWhere(['like', 'last_name', $this->last_name])
			->andFilterWhere(['like', 'phone', $this->phone])
			->andFilterWhere(['like', 'access_token', $this->access_token])
			->andFilterWhere(['like', 'last_login_ip', $this->last_login_ip])
			->andFilterWhere(['like', 'device_id', $this->device_id])
			->andFilterWhere(['like', 'serial_number', $this->serial_number])
			->andFilterWhere(['like', 'model', $this->model])
			->andFilterWhere(['like', 'manufacture', $this->manufacture])
			->andFilterWhere(['like', 'brand', $this->brand])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'password', $this->password]);
		
		return $dataProvider;
	}
}
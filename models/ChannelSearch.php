<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Channel;

/**
 * ChannelSearch represents the model behind the search form about `app\models\Channel`.
 */
class ChannelSearch extends Channel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'channel_id', 'chat_id', 'admin_id', 'member_count', 'last_update', 'channel_group', 'category_id', 'bot_id', 'ban_until', 'participate_at_1', 'participate_at_2', 'participate_at_3', 'participate_at_4', 'active'], 'integer'],
			[['name', 'title', 'description'], 'safe'],
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
		$query = Channel::find();
		
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
			'id'            => $this->id,
			'channel_id'    => $this->channel_id,
			'chat_id'       => $this->chat_id,
			'admin_id'      => $this->admin_id,
			'member_count'  => $this->member_count,
			'last_update'   => $this->last_update,
			'channel_group' => $this->channel_group,
			'category_id'   => $this->category_id,
			'bot_id'        => $this->bot_id,
			'ban_until'     => $this->ban_until,
			'active'        => $this->active,
		]);
		
		if ($this->participate_at_1)
			$query->andFilterWhere([
				'participate_at_1' => $this->participate_at_1,
			]);
		
		if ($this->participate_at_2)
			$query->andFilterWhere([
				'participate_at_2' => $this->participate_at_2,
			]);
		
		if ($this->participate_at_3)
			$query->andFilterWhere([
				'participate_at_3' => $this->participate_at_3,
			]);
		
		if ($this->participate_at_4)
			$query->andFilterWhere([
				'participate_at_4' => $this->participate_at_4,
			]);
		
		$query->andFilterWhere(['like', 'name', $this->name])
			->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'description', $this->description]);
		
		return $dataProvider;
	}
}
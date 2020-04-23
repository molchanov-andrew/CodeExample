<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\LotteryTimer;

/**
 * LotteryTimerSearch represents the model behind the search form of `common\models\records\LotteryTimer`.
 */
class LotteryTimerSearch extends LotteryTimer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'timeCorrection', 'dayOfWeek', 'lotteryId'], 'integer'],
            [['time', 'resultName', 'timezone', 'created', 'updated'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = LotteryTimer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if(empty($this->lotteryId) && isset($params['parentId'])){
            $this->lotteryId = $params['parentId'];
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'time' => $this->time,
            'timeCorrection' => $this->timeCorrection,
            'dayOfWeek' => $this->dayOfWeek,
            'lotteryId' => $this->lotteryId,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['like', 'resultName', $this->resultName])
            ->andFilterWhere(['like', 'timezone', $this->timezone]);

        return $dataProvider;
    }
}

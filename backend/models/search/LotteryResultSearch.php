<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\LotteryResult;

/**
 * LotteryResultSearch represents the model behind the search form of `common\models\records\LotteryResult`.
 */
class LotteryResultSearch extends LotteryResult
{
    public $limit = 20;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'lotteryId', 'lotteryTimerId', 'limit'], 'integer'],
            [['uniqueResultId', 'mainNumbers', 'additionalNumbers', 'bonusNumbers', 'date', 'created', 'updated'], 'safe'],
            [['jackpot'], 'number'],
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
        $query = LotteryResult::find();

        // add conditions that should always apply here

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
            'pagination' => [
                'pageSize' => (int)$this->limit,
            ],
        ]);

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
            'status' => $this->status,
            'date' => $this->date,
            'jackpot' => $this->jackpot,
            'created' => $this->created,
            'updated' => $this->updated,
            'lotteryId' => $this->lotteryId,
            'lotteryTimerId' => $this->lotteryTimerId,
        ]);

        $query->andFilterWhere(['like', 'uniqueResultId', $this->uniqueResultId])
            ->andFilterWhere(['like', 'mainNumbers', $this->mainNumbers])
            ->andFilterWhere(['like', 'additionalNumbers', $this->additionalNumbers])
            ->andFilterWhere(['like', 'bonusNumbers', $this->bonusNumbers]);

        return $dataProvider;
    }
}

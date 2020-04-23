<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\BrokerToLottery;

/**
 * BrokerToLotterySearch represents the model behind the search form of `common\models\records\BrokerToLottery`.
 */
class BrokerToLotterySearch extends BrokerToLottery
{
    public $limit = 20;
    public $lotteryName;
    public $brokerName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'brokerId', 'lotteryId', 'syndicat', 'limit'], 'integer'],
            [['price'], 'number'],
            [['lotteryName', 'brokerName', 'url'], 'safe'],
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
        $query = BrokerToLottery::find()->with(['lottery','broker','discounts','systematics']);

        // add conditions that should always apply here

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (int)$this->limit,
            ],
        ]);

        if(empty($this->lotteryId) && isset($params['parentId'], $params['parentEntity'])){
            if($params['parentEntity'] === 'lottery'){
                $this->lotteryId = $params['parentId'];
            }
            if($params['parentEntity'] === 'broker'){
                $this->brokerId = $params['parentId'];
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->lotteryName !== null){
            $query->joinWith('lottery l',true,'INNER JOIN')->andWhere(['like','l.name',$this->lotteryName]);
        }
        if($this->brokerName !== null){
            $query->joinWith('broker b',true,'INNER JOIN')->andWhere(['like','b.name',$this->brokerName]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'brokerId' => $this->brokerId,
            'lotteryId' => $this->lotteryId,
            'syndicat' => $this->syndicat,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}

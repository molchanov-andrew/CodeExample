<?php

namespace backend\models\search;

use common\models\records\Broker;
use common\models\records\BrokerToLottery;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\Lottery;
use yii\db\ActiveQuery;

/**
 * LotterySearch represents the model behind the search form of `common\models\records\Lottery`.
 */
class LotterySearch extends Lottery
{
    public $brokerName;
    public $limit = 20;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'published', 'systematic', 'overallChance', 'logoImageId', 'parentLotteryId','limit', 'countryId'], 'integer'],
            [['name', 'countryId', 'mainNumbers', 'brokerName', 'mainNumbersToCheck', 'mainNumbersDescription', 'addNumbers', 'addNumbersToCheck', 'addNumbersDescription', 'chanceToWin', 'numberAmounts', 'created', 'updated'], 'safe'],
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
        $query = Lottery::find()->with(['logoImage','latestLotteryResult', 'country'])->with(['brokerToLotteries' => function(ActiveQuery $query){
            return $query->with('broker');
        }]);

        // add conditions that should always apply here

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (int)$this->limit,
            ],
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->brokerName)){
            $brokers = Broker::find()->select('id')->andWhere(['like','name',$this->brokerName])->all();
            if(empty($brokers)){
                $query->andWhere('1=0');
            } else {
                $btls = BrokerToLottery::find()->select('lotteryId')->andWhere(['brokerId' => array_column($brokers,'id')])->all();
                if(empty($btls)){
                    $query->andWhere('1=0');
                } else {
                    $query->andWhere(['id' => array_column($btls,'lotteryId')]);
                }
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'Lottery.id' => $this->id,
            'Lottery.published' => $this->published,
            'Lottery.jackpot' => $this->jackpot,
            'Lottery.systematic' => $this->systematic,
            'Lottery.overallChance' => $this->overallChance,
            'Lottery.logoImageId' => $this->logoImageId,
            'Lottery.parentLotteryId' => $this->parentLotteryId,
            'Lottery.created' => $this->created,
            'Lottery.updated' => $this->updated,
            'Lottery.countryId' => $this->countryId,
        ]);

        $query->andFilterWhere(['like', 'Lottery.name', $this->name])
            ->andFilterWhere(['like', 'Lottery.mainNumbers', $this->mainNumbers])
            ->andFilterWhere(['like', 'Lottery.mainNumbersToCheck', $this->mainNumbersToCheck])
            ->andFilterWhere(['like', 'Lottery.mainNumbersDescription', $this->mainNumbersDescription])
            ->andFilterWhere(['like', 'Lottery.addNumbers', $this->addNumbers])
            ->andFilterWhere(['like', 'Lottery.addNumbersToCheck', $this->addNumbersToCheck])
            ->andFilterWhere(['like', 'Lottery.addNumbersDescription', $this->addNumbersDescription])
            ->andFilterWhere(['like', 'Lottery.chanceToWin', $this->chanceToWin])
            ->andFilterWhere(['like', 'Lottery.numberAmounts', $this->numberAmounts])
            ->andFilterWhere(['like', 'Lottery.numberAmounts', $this->numberAmounts]);

        return $dataProvider;
    }
}

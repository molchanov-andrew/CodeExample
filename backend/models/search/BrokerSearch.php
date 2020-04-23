<?php

namespace backend\models\search;

use common\models\records\BrokerToLottery;
use common\models\records\Lottery;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\Broker;
use yii\db\ActiveQuery;

/**
 * BrokerSearch represents the model behind the search form of `common\models\records\Broker`.
 */
class BrokerSearch extends Broker
{
    public $lotteryName;
    public $limit = 20;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'published', 'clicks', 'minimalDeposit', 'disableIframe', 'systematic', 'scanTicket', 'chat', 'marks', 'summaryMarks', 'statusId', 'imageId', 'limit'], 'integer'],
            [['name', 'site', 'year', 'created', 'updated','lotteryName'], 'safe'],
            [['security', 'support', 'gameplay', 'promotions', 'withdrawals', 'usability', 'gameSelection', 'discounts'], 'number'],
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
        $query = Broker::find()->with(['image','status'])->with(['brokerToLotteries' => function(ActiveQuery $query){
            return $query->with('lottery');
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
        if(!empty($this->lotteryName)){
            $lotteries = Lottery::find()->select('id')->andWhere(['like','name',$this->lotteryName])->all();
            if(empty($lotteries)){
                $query->andWhere('1=0');
            } else {
                $btls = BrokerToLottery::find()->select('brokerId')->andWhere(['lotteryId' => array_column($lotteries,'id')])->all();
                if(empty($btls)){
                    $query->andWhere('1=0');
                } else {
                    $query->andWhere(['id' => array_column($btls,'brokerId')]);
                }
            }
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'Broker.id' => $this->id,
            'Broker.published' => $this->published,
            'Broker.clicks' => $this->clicks,
            'Broker.minimalDeposit' => $this->minimalDeposit,
            'Broker.disableIframe' => $this->disableIframe,
            'Broker.systematic' => $this->systematic,
            'Broker.scanTicket' => $this->scanTicket,
            'Broker.chat' => $this->chat,
            'Broker.security' => $this->security,
            'Broker.support' => $this->support,
            'Broker.gameplay' => $this->gameplay,
            'Broker.promotions' => $this->promotions,
            'Broker.withdrawals' => $this->withdrawals,
            'Broker.usability' => $this->usability,
            'Broker.gameSelection' => $this->gameSelection,
            'Broker.discounts' => $this->discounts,
            'Broker.marks' => $this->marks,
            'Broker.summaryMarks' => $this->summaryMarks,
            'Broker.created' => $this->created,
            'Broker.updated' => $this->updated,
            'Broker.statusId' => $this->statusId,
            'Broker.imageId' => $this->imageId,
        ]);

        $query->andFilterWhere(['like', 'Broker.name', $this->name])
            ->andFilterWhere(['like', 'Broker.site', $this->site])
            ->andFilterWhere(['like', 'Broker.year', $this->year]);

        return $dataProvider;
    }
}

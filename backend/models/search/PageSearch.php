<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\Page;
use yii\db\ActiveQuery;

/**
 * PageSearch represents the model behind the search form of `common\models\records\Page`.
 */
class PageSearch extends Page
{
    public $limit = 20;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'promotingBrokerId', 'brokerId', 'lotteryId', 'limit'], 'integer'],
            [['name', 'module', 'created', 'updated'], 'safe'],
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
        $query = Page::find()->with(['broker','lottery','country','pageContents' => function(ActiveQuery $query){
            return $query->with('language');
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'promotingBrokerId' => $this->promotingBrokerId,
            'created' => $this->created,
            'updated' => $this->updated,
            'brokerId' => $this->brokerId,
            'lotteryId' => $this->lotteryId,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'module', $this->module]);

        return $dataProvider;
    }
}

<?php

namespace backend\models\search;

use common\models\records\Currency;
use common\models\records\Language;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\Country;

/**
 * CountrySearch represents the model behind the search form of `common\models\records\Country`.
 */
class CountrySearch extends Country
{
    public $limit = 20;

    public $languageName;
    public $currencyName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'currencyId', 'languageId', 'limit'], 'integer'],
            [['name', 'iso', 'created', 'updated','currencyName','languageName'], 'safe'],
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
        $query = Country::find()->with(['currency','language']);

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

        if($this->languageName !== null){
            $languages = Language::find()->select('id')->andWhere(['like','name',$this->languageName])->all();
            if(empty($languages)){
                $query->andWhere('0=1');
            } else {
                $query->andWhere(['languageId' => array_column($languages,'id')]);
            }
        }

        if($this->currencyName !== null){
            $currencies = Currency::find()->select('id')->andWhere(['like','name',$this->currencyName])->all();
            if(empty($currencies)){
                $query->andWhere('0=1');
            } else {
                $query->andWhere(['currencyId' => array_column($currencies,'id')]);
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created' => $this->created,
            'updated' => $this->updated,
            'currencyId' => $this->currencyId,
            'languageId' => $this->languageId,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'iso', $this->iso]);

        return $dataProvider;
    }
}

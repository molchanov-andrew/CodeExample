<?php

namespace backend\models\search;

use common\models\records\Broker;
use common\models\records\Language;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\BrokerPositionToLanguage;
use yii\db\ActiveQuery;

/**
 * BrokerPositionToLanguageSearch represents the model behind the search form of `common\models\records\BrokerPositionToLanguage`.
 */
class BrokerPositionToLanguageSearch extends BrokerPositionToLanguage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brokerId', 'languageId', 'position'], 'integer'],
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
        $query = Broker::find()->with(['brokerPositionToLanguage' => function(ActiveQuery $query){
            return $query->andWhere(['languageId' => $this->languageId]);
        },'status']);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]],
            'pagination' => false,
        ]);

        $this->load($params);

        if($this->languageId === null){
            $firstLanguage = Language::find()->one();
            if($firstLanguage === null){
                $query->andWhere('1=0');
            }
            $this->languageId = $firstLanguage->id;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $models = $dataProvider->getModels();
        uasort($models,function(Broker $v1, Broker $v2) {
            if($v1->brokerPositionToLanguage === null || $v2->brokerPositionToLanguage === null){
                return 0;
            }
            if ($v1->brokerPositionToLanguage->position === $v2->brokerPositionToLanguage->position) {
                return 0;
            }
            return ($v1->brokerPositionToLanguage->position < $v2->brokerPositionToLanguage->position)? -1: 1;
        });
        $dataProvider->setModels($models);

        return $dataProvider;
    }
}

<?php

namespace backend\models\search;

use common\models\records\Language;
use common\models\records\Lottery;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\LotteryPositionToLanguage;
use yii\db\ActiveQuery;

/**
 * LotteryPositionToLanguageSearch represents the model behind the search form of `common\models\records\LotteryPositionToLanguage`.
 */
class LotteryPositionToLanguageSearch extends LotteryPositionToLanguage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lotteryId', 'languageId', 'position'], 'integer'],
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
        $query = Lottery::find()->with(['lotteryPositionToLanguage' => function(ActiveQuery $query){
            return $query->andWhere(['languageId' => $this->languageId]);
        }]);

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
        uasort($models,function(Lottery $v1, Lottery $v2) {
            if($v1->lotteryPositionToLanguage === null || $v2->lotteryPositionToLanguage === null){
                return 0;
            }
            if ($v1->lotteryPositionToLanguage->position === $v2->lotteryPositionToLanguage->position) {
                return 0;
            }
            return ($v1->lotteryPositionToLanguage->position < $v2->lotteryPositionToLanguage->position)? -1: 1;
        });
        $dataProvider->setModels($models);

        return $dataProvider;
    }
}

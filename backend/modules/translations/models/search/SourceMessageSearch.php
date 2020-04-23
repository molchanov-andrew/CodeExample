<?php
namespace backend\modules\translations\models\search;

use backend\modules\translations\Module;
use common\models\records\Message;
use common\models\records\SourceMessage;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class SourceMessageSearch extends SourceMessage
{
    const STATUS_TRANSLATED = 1;
    const STATUS_NOT_TRANSLATED = 2;

    /**
     * @var int
     */
    public $status;
    /**
     * @var string
     */
    public $translation;

    public $language;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['language','safe'],
            ['category', 'safe'],
            ['message', 'safe'],
            ['status', 'safe'],
            ['translation', 'safe'],
        ];
    }

    /**
     * Search models
     *
     * @param array|null $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SourceMessage::find();
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if(empty($this->language)){
            $query->joinWith('message')->andFilterWhere(['like', 'message', $this->message]);

            if ($this->status == static::STATUS_TRANSLATED) {
                $query->translated();
            }
            if ($this->status == static::STATUS_NOT_TRANSLATED) {
                $query->notTranslated();
            }
        } else {
            $query->joinWith(['customMessage' => function(ActiveQuery $query){
                return $query->where(['language' => $this->language]);
            }])->andFilterWhere(['like', 'customMessage', $this->message]);

            if ($this->status == static::STATUS_TRANSLATED) {
                $query->translatedByLanguage($this->language);
            }
            if ($this->status == static::STATUS_NOT_TRANSLATED) {
                $query->notTranslatedByLanguage($this->language);
            }
        }

        $query
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', Message::tableName().'.translation', $this->translation]);
        return $dataProvider;
    }

    /**
     * Returns status
     *
     * @param null $id
     * @return array|mixed
     */
    public static function getStatus($id = null)
    {
        $statuses = [
            self::STATUS_TRANSLATED => 'Translated',
            self::STATUS_NOT_TRANSLATED => 'Not translated',
        ];
        if ($id !== null) {
            return ArrayHelper::getValue($statuses, $id, null);
        }
        return $statuses;
    }
}
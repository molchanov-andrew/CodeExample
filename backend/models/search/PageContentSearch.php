<?php

namespace backend\models\search;

use common\models\records\Page;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\records\PageContent;

/**
 * PageContentSearch represents the model behind the search form of `common\models\records\PageContent`.
 */
class PageContentSearch extends PageContent
{
    public $languageName;
    public $limit = 20;

    public $notPublished;
    public $module;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'title', 'keywords', 'description', 'languageName', 'additionalDescription', 'alternativeDescription', 'content', 'created', 'updated','notPublished', 'module'], 'safe'],
            [['published', 'imageId', 'pageId', 'limit'], 'integer'],
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
        $query = PageContent::find()->with(['image','language']);

        // add conditions that should always apply here

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => (int)$this->limit,
            ],
        ]);

        if(empty($this->pageId) && isset($params['parentId'])){
            $this->pageId = $params['parentId'];
        }

        if($this->pageId === null){
            $query->with('page');
        }

        if($this->module !== null){
            $moduledPages = Page::find()->select(['id'])->andWhere(['module' => $this->module])->all();
            if(!empty($moduledPages)){
                $moduledPagesIds = array_column($moduledPages,'id');
                $query->andWhere(['pageId' => $moduledPagesIds]);
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if($this->notPublished == 1){
            $query->andFilterWhere(['published' => 0]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'created' => $this->created,
            'updated' => $this->updated,
            'imageId' => $this->imageId,
            'pageId' => $this->pageId,
        ]);
        if(!empty($this->languageName)){
            $query->joinWith('language l',true,'INNER JOIN')->andWhere(['like', 'l.name', $this->languageName]);
        }

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'additionalDescription', $this->additionalDescription])
            ->andFilterWhere(['like', 'alternativeDescription', $this->alternativeDescription])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}

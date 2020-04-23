<?php
/**
 * Date: 6/28/18
 */

namespace common\models\queries;


use common\models\records\Page;
use yii\db\ActiveQuery;

class PageQuery extends ActiveQuery
{
    public function menuModules()
    {
        return $this->andWhere(['module' => [
            Page::MODULE_HOME,
            Page::MODULE_ABOUT_US,
            Page::MODULE_CONTACT_US,
            Page::MODULE_LOTTERIES_TABLE,
            Page::MODULE_BROKERS_TABLE,
            Page::MODULE_LAST_RESULTS_TABLE,
            Page::MODULE_BUY_ONLINE_TABLE,
            Page::MODULE_TOOLS_LIST,
            Page::MODULE_ARTICLES_LIST,
            Page::MODULE_COMPARE_BROKERS,
        ]]);
    }

    public function articles()
    {
        return $this->andWhere(['module' => Page::MODULE_ARTICLE]);
    }

    public function tools()
    {
        return $this->andWhere(['module' => [
            Page::MODULE_TOOLS_HOT_NUMBERS,
            Page::MODULE_TOOLS_RANDOM_NUMBERS
        ]]);
    }

    public function results()
    {
        return $this->andWhere([
            'module' => Page::MODULE_LOTTERY_RESULT
        ]);
    }

    public function countriesResultPages()
    {
        return $this->andWhere(['module' => Page::MODULE_RESULTS_BY_COUNTRY]);
    }

    public function joinWithCurrentLanguagePageContent()
    {
        return $this->joinWith(['pageContentByLanguage' => function(PageContentQuery $query) {
            return $query->onCondition(['PageContent.languageId' => \Yii::$app->pageData->currentLanguage->id, 'PageContent.published' => 1]);
        }],true,'INNER JOIN');
    }

    public function withCountry()
    {
        return $this->with(['country' => function(ActiveQuery $query){
            return $query->with('image');
        }]);
    }
}
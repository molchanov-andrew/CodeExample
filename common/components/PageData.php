<?php
/**
 * Date: 6/28/18
 */

namespace common\components;


use common\models\queries\PageContentQuery;
use common\models\records\Currency;
use common\models\records\Language;
use common\models\records\Page;
use common\models\records\PageContent;
use common\models\records\Setting;
use yii\base\Model;
/**
 * @property PageContent $pageContent
 * @property Page[] $menuPages
 * @property Language $currentLanguage
 * @property Language[] $languages
 * @property Currency[] $currencies
 * @property Setting[] $settings
 */
class PageData extends Model
{
    private $_pageContent;
    private $_currentLanguage;
    private $_languages;
    private $_menuPages;
    private $_currencies;
    private $_settings;

    /**
     * @return mixed
     */
    public function getPageContent()
    {
        return $this->_pageContent;
    }

    /**
     * @param mixed $pageContent
     */
    public function setPageContent($pageContent)
    {
        $this->_pageContent = $pageContent;
    }

    /**
     * @return Language[]
     */
    public function getLanguages(): array
    {
        if($this->_languages === null){
            $this->_languages = Language::find()->andWhere(['published' => 1])->indexBy('id')->all();
        }
        return $this->_languages;
    }

    /**
     * @param mixed $languages
     */
    public function setLanguages($languages)
    {
        $this->_languages = $languages;
    }

    /**
     * @return mixed
     */
    public function getMenuPages()
    {
        if($this->_menuPages === null){
            $this->_menuPages = Page::findMenuPages()->with(['pageContentByLanguage' => function(PageContentQuery $query){
                return $query->where(['languageId' => $this->pageContent->language->id]);
            }])->all();
        }
        return $this->_menuPages;
    }

    /**
     * @param mixed $menuPages
     */
    public function setMenuPages($menuPages)
    {
        $this->_menuPages = $menuPages;
    }

    /**
     * @return mixed
     */
    public function getCurrencies()
    {
        if($this->_currencies === null){
            $this->_currencies = Currency::find()->published()->indexBy('iso')->all();
        }
        return $this->_currencies;
    }

    /**
     * @param mixed $currencies
     */
    public function setCurrencies($currencies)
    {
        $this->_currencies = $currencies;
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        if($this->_settings === null){
            $this->_settings = Setting::find()->indexBy('name')->all();
        }

        return $this->_settings;
    }

    /**
     * @return mixed
     */
    public function getCurrentLanguage()
    {
        return $this->_currentLanguage;
    }

    /**
     * @param mixed $currentLanguage
     */
    public function setCurrentLanguage(Language $currentLanguage)
    {
        \Yii::$app->language = $currentLanguage->iso;
        $this->_currentLanguage = $currentLanguage;
    }

    public function getLanguageIsoById($languageId)
    {
        return isset($this->getLanguages()[$languageId]) ? $this->getLanguages()[$languageId]->iso : null;
    }
}
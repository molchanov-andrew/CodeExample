<?php

namespace common\models\records\result;


use common\models\records\PageContent;
use common\models\records\SourceMessage;
use Yii;

/**
 * @property ResultPage $page
 * @property \DateTime $parentNativeDatetime
 */

class ResultPageContent extends PageContent
{
    private $_urlCreated = false;
    protected $_urlByMonth;
    protected $_urlByYear;
    protected $_languageIso;
    protected $_nativeDatetime;

    public function makeUrl()
    {
        if(!$this->_urlCreated){
            $parentResult = $this->page->getParentResult();
            $url = $this->_makeUrlByMonth() . '/' . $this->getParentNativeDatetime()->format('Y-m-d');
            if($parentResult->lotteryTimer->resultName !== null) {
                $url .= '/' . Yii::t(SourceMessage::CATEGORY_GENERAL, $parentResult->lotteryTimer->resultName, [], $this->getLanguageIso());
            }
            $this->url = $url;
            $this->_urlCreated = true;
        }

        return $this->url;
    }

    protected function _makeUrlByMonth()
    {
        $month = mb_strtolower(Yii::t(SourceMessage::CATEGORY_GENERAL, $this->getParentNativeDatetime()->format('F'), [], $this->getLanguageIso()), 'UTF-8');
        return $this->_makeUrlByYear() . '/' . $month;
    }

    protected function _makeUrlByYear()
    {

        return "{$this->url}/{$this->getParentNativeDatetime()->format('Y')}";
    }

    public function getLanguageIso()
    {
        if($this->_languageIso === null){
            $this->_languageIso = Yii::$app->pageData->getLanguageIsoById($this->languageId);
        }
        return $this->_languageIso;
    }

    public function getParentNativeDatetime()
    {
        if($this->_nativeDatetime === null){
            $parentResult = $this->page->getParentResult();
            $this->_nativeDatetime = $parentResult->getNativeDatetime();
        }
        return $this->_nativeDatetime;
    }
}
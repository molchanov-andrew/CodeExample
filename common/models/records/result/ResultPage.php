<?php

namespace common\models\records\result;

use common\models\records\LotteryResult;
use common\models\records\Page;
use common\models\records\PageContent;

/**
 * @property LotteryResult $_parentResult
 * @property LotteryResult $parentResult
 * @property ResultPageContent[] $pageContents
*/
class ResultPage extends Page
{
    protected $_parentResult;
    protected $_contentPages;

    /**
     * @param null|int $language if not set - generate all languages.
     */
    public function generateContentPages($language = null)
    {
        if($language === null) {
            $languageIds = array_column(\Yii::$app->pageData->getLanguages(),'id');
            foreach ($languageIds as $languageId) {
                /** @var PageContent $pc */
                $pc = isset(\Yii::$app->resultData->getResultPageContents()[$languageId]) ? \Yii::$app->resultData->getResultPageContents()[$languageId] : null;
                if($pc === null){
                    throw new \RuntimeException("Page content for language {$languageId} not set.");
                }
                $pageContent = new ResultPageContent(...$pc->toArray());
                $pageContent->populateRelation('page', $this);
                $pageContent->makeUrl($this->_parentResult->getNativeDatetime(), \Yii::$app->pageData->getLanguageIsoById($languageId), $this->_parentResult->lotteryTimer->resultName);
                $this->_contentPages[$languageId] = $pageContent;
            }
        }
    }

    /**
     * @param mixed $contentPages
     * @return ResultPage
     */
    public function setContentPages($contentPages)
    {
        $this->_contentPages = $contentPages;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentPages()
    {
        if($this->_contentPages === null){
            $this->generateContentPages();
        }
        return $this->_contentPages;
    }

    /**
     * @param mixed $parentResult
     * @return ResultPage
     */
    public function setParentResult($parentResult)
    {
        $this->_parentResult = $parentResult;
        return $this;
    }

    /**
     * @return LotteryResult
     */
    public function getParentResult()
    {
        return $this->_parentResult;
    }
}
<?php
/**
 * Date: 8/18/18
 */

namespace common\components;


use common\models\queries\PageContentQuery;
use common\models\records\Page;
use yii\base\Model;

class ResultData extends Model
{
    private $_prefixes;
    private $_resultPageContents;

    /**
     * @return mixed
     */
    public function getPrefixes()
    {
        if($this->_prefixes === null){
            $this->_prefixes = array_column($this->getResultPageContents(), 'url', 'languageId');
        }
        return $this->_prefixes;
    }

    /**
     * @return mixed
     */
    public function getResultPageContents()
    {
        if($this->_resultPageContents === null) {
            /** @var Page $resultsPage */
            $resultsPage = Page::find()->results()->with(['pageContents'])->one();
            $this->_resultPageContents = $resultsPage;
        }
        return $this->_resultPageContents;
    }
}
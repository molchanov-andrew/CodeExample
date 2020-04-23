<?php
namespace backend\models\grid;

use yii\bootstrap\Html;
use yii\grid\CheckboxColumn;

class CustomCheckboxColumn extends CheckboxColumn
{
    public $filter;
    public $limitFilter;

    public function init()
    {
        $this->headerOptions['class'] = 'checkbox-column';
        $this->contentOptions['class'] = 'checkbox-column';
        $this->filterOptions['class'] = 'checkbox-column';
        parent::init();
    }

    protected function renderFilterCellContent()
    {
        if($this->limitFilter === true){
            return $this->_renderLimitFilter();
        }
        return !empty($this->filter) ? (string)$this->filter : parent::renderFilterCellContent();
    }

    private function _renderLimitFilter()
    {
        return Html::activeInput('text', $this->grid->filterModel, 'limit', ['class'=>'form-control']);
    }
}
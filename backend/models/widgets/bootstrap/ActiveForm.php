<?php

namespace backend\models\widgets\bootstrap;


use backend\widgets\translations\Translate;
use yii\bootstrap\Html;

/**
 * Class ActiveForm
 * Extended for making actions like change multiple grid.
 * Used for getting all activeFields added to form and output their ids into data-field attribute separated by comma.
 * @package backend\models\widgets\bootstrap
 */
class ActiveForm extends \yii\bootstrap\ActiveForm
{
    protected $_fieldIds;


    public function beforeRun()
    {
        if(!empty($this->_fieldIds)){
            $this->options['data-fields'] = implode(',',$this->_fieldIds);
        }

        return parent::beforeRun();
    }

    public function field($model, $attribute, $options = [])
    {
        if(isset($options['translationAddon']['message'],$options['translationAddon']['category'])){
            $translationButton = Translate::widget(['message' => $options['translationAddon']['message'], 'category' => $options['translationAddon']['category']]);
            $options['inputTemplate'] = $options['inputTemplate'] ?? "<div class=\"input-group\"><span class=\"input-group-addon\">{$translationButton}</span>{input}</div>";
        }
        unset($options['translationAddon']);
        $field = parent::field($model, $attribute, $options);
        $this->_fieldIds[] = $field->options['id'] ?? Html::getInputId($model, $attribute);
        return $field;
    }
}
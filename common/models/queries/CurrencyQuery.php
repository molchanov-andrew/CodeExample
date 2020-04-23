<?php
/**
 * Date: 6/28/18
 */

namespace common\models\queries;


use yii\db\ActiveQuery;

class CurrencyQuery extends ActiveQuery
{
    public function published()
    {
        return $this->andWhere(['published' => 1]);
    }
}
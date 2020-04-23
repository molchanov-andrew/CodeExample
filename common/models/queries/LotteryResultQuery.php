<?php
/**
 * Date: 7/6/18
 */

namespace common\models\queries;


use yii\db\ActiveQuery;
use yii\db\Expression;

class LotteryResultQuery extends ActiveQuery
{
    public function monthlyThisYear()
    {
        $now = new \DateTime('now');
        return $this->andWhere(['YEAR(date)' => $now->format('Y')])->groupBy(new Expression('MONTH(date)'));
    }

    public function yearly()
    {
        return $this->groupBy(new Expression('YEAR(date)'));
    }
}
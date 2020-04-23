<?php
/**
 * Date: 6/28/18
 */

namespace common\models\queries;


use yii\db\ActiveQuery;

class PageContentQuery extends ActiveQuery
{
    public function published()
    {
        return $this->andWhere(['published' => 1]);
    }

    public function url($url)
    {
        return $this->andWhere(['url' => $url]);
    }
}
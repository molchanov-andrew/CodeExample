<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 1/31/18
 * Time: 1:29 PM
 */

namespace app\modules\rest\models;


use yii\db\ActiveQueryInterface;

class PActiveActionHelper
{
    public static function appendExpands(ActiveQueryInterface $query,$expands = null)
    {
        if(!empty($expands))
        {
            foreach ($expands as $expand) {
                $query->with($expand);
            }
        }
        return $query;
    }
}
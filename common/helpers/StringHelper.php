<?php
namespace common\helpers;

class StringHelper extends \yii\helpers\StringHelper
{
    /**
     * Converts
     * @param $input
     * @return string
     */
    public static function decamelize(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    public static function camelize(string $input,bool $capitalizeFirstCharacter = false)
    {
        $str = str_replace('-', '', ucwords($input, '-'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    /**
     * Gets model name from fullpath model class name.
     * @param $string
     * @return mixed
     */
    public static function getModelName(string $string)
    {
        $tmp = explode('\\',$string);
        $class = end($tmp);
        return $class;
    }
}
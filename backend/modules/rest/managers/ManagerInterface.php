<?php
namespace backend\modules\rest\managers;
/**
 * PUT - pull data there;
 * OPTIONS - show data list available;
 * PATCH - push data from here;
 * Interface ManagerInterface
 * @package APIEntities
 */
interface ManagerInterface{

    /**
     * Getting request like :
     * [
     *      'identifier' => ex. 95,
     *      'languageIso' => ex. 'en',
     *      'data' => [
     *          ... ex. 'page_id' => 5, 'something else' => 'value of something else'
     *      ]
     * ]
     * @param $httpResponse
     * @return array
     */
    public function put($httpResponse);

    /**
     * Returns list of entities.
     * @param $httpResponse
     * @return array ex. : [
     *      'status' => 'success',
     *      'data' => [
     *          'entityKey(ex. 95)' => [
     *              'name' => ex. 'Lottery Page'
     *              'category' => ex. 'Lottery pages' - for categorizing in ttms, can be used by any strings, later could be sorted by them.
     *              'contents' => [
     *                  ... languages available ex 'en','ru','es'
     *              ]
     *              'thirdEntity' => ask sysadmin for it , it's customized by ttms.
     *          ]
     *      ]
     * ]
     */
    public function options($httpResponse);

    /**
     * Get's request like [
     *      'identifier' => ex. 95,
     *      'languageIso' => ex. 'en'
     * ]
     * @param $httpResponse
     * @return array of data requested.
     */
    public function patch($httpResponse);
}
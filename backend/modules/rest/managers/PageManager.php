<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 3/29/18
 * Time: 4:14 PM
 */

namespace backend\modules\rest\managers;


use common\models\records\Language;
use common\models\records\Page;
use common\models\records\PageContent;

class PageManager implements ManagerInterface
{
    const IDENTIFIER = 'id';
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
    public function put($httpResponse)
    {
        if(isset($httpResponse['identifier']) && isset($httpResponse['languageIso'])) {
            /** @var Page $page */
            $page = Page::find()->with(['pageContents' => function($query){
                return $query->indexBy('languageId');
            }])->andWhere([self::IDENTIFIER => $httpResponse['identifier']])->one();
            $languages = Language::find()->all();
            if($languageKey = array_search($httpResponse['languageIso'],array_column($languages,'iso','id'))){
                if(isset($page->pageContents[$languageKey])){
                    $page->pageContents[$languageKey]->load($httpResponse['data'],'');
                    return $page->pageContents[$languageKey]->save() ? ['status' => 'success'] : ['status' => 'error', 'message' => 'validation failed.'];
                } else {
                    $newPageContent = new PageContent([
                        'languageId' => $languageKey,
                        'pageId' => $page->id,
                    ]);
                    $newPageContent->load($httpResponse['data'],'');
                    $result = $newPageContent->save();
                    return $result ? ['status' => 'success'] : ['status' => 'error', 'message' => 'validation failed.'];
                }
            } else {
                return ['status' => 'error', 'message' => 'page not found'];
            }

        } else if(isset($httpResponse['data']['url'])) {
            $pageUrl = $httpResponse['data']['url'];
            unset($httpResponse['data']['url']);
            $pageContent = PageContent::find()->andWhere(['url' => $pageUrl])->one();
            $pageContent->load($httpResponse['data'],'');
            return $pageContent->save() ? ['status' => 'success'] : ['status' => 'error', 'message' => 'validation failed.'];
        } else{
            return ['status' => 'error', 'message' => 'page not found'];
        }
    }

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
    public function options($httpResponse)
    {

        /** @var Page[] $pages */
        $pages = Page::find()->with(['pageContents' => function($query){
            return $query->indexBy('languageId');
        }])->indexBy('id')->all();
        $languages = Language::find()->all();
        $languageIsoArray = array_column($languages,'iso','id');
        $result = ['status' => 'success', 'data' => []];
        foreach ($pages as $page) {
            $result['data'][$page->id] = [
                'name' => $page->name,
                'category' => $page->module . ' page',
                'thirdEntity' => 'page',
                'contents' => []
            ];
            foreach ($page->pageContents as $pageContent) {
                $result['data'][$page->id]['contents'][] = $languageIsoArray[$pageContent->languageId];
            }
        }
        return $result;
    }

    /**
     * Get's request like [
     *      'identifier' => ex. 95,
     *      'languageIso' => ex. 'en'
     * ]
     * @param $httpResponse
     * @return array of data requested.
     */
    public function patch($httpResponse)
    {
        $page = Page::find()->with(['pageContents' => function($query){
            return $query->indexBy('languageId');
        }])->indexBy('id')->andWhere([self::IDENTIFIER => $httpResponse['identifier']])->one();
        $languages = Language::find()->all();
        $languageIsoArray = array_column($languages,'id','iso');
        /** @var Page $page */
        if(isset($page->pageContents[$languageIsoArray[$httpResponse['languageIso']]])){
            $data = $page->pageContents[$languageIsoArray[$httpResponse['languageIso']]]->toArray();
            $data['name'] = $page->name;
            return ['status' => 'success', 'data' => $data];
        }
        return ['status' => 'error', 'message' => 'Page not found'];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 1/10/18
 * Time: 9:54 AM
 */

namespace app\modules\rest\controllers;

use app\modules\rest\models\PActiveController;

class PageContentController extends PActiveController
{
    public $modelClass = 'app\models\records\PageContent';
    public $searchModelClass = 'app\models\search\PageContentSearch';
}
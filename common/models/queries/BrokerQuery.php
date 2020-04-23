<?php
/**
 * Date: 6/28/18
 */

namespace common\models\queries;


use common\models\records\BrokerStatus;
use yii\db\ActiveQuery;

class BrokerQuery extends ActiveQuery
{
    public function selectAll()
    {
        return $this->select('Broker.*');
    }

    public function published()
    {
        return $this->andWhere(['published' => 1]);
    }

    public function withBrokersTableRelatedData()
    {
        return $this->with([
            'image',
            'status' => function(ActiveQuery $query){
                return $query->with('listImage');
            },
            'languages' => function(ActiveQuery $query){
                return $query->with('image');
            },
            'paymentMethods'=> function(ActiveQuery $query){
                return $query->with('image');
            },
            'bonuses',
            'brokerToLotteries' => function(ActiveQuery $query){
                return $query->with(['lottery' => function(LotteryQuery $query){
                    return $query->with('logoImage');
                }]);
            }
        ])->withReviewPage();
    }

    public function withMuchRelatedData()
    {
        return $this->with([
            'paymentMethods'=> function(ActiveQuery $query){
                return $query->with('image');
            },
            'bonuses',
            'brokerToLotteries' => function(ActiveQuery $query){
                return $query->joinWith(['lottery' => function(LotteryQuery $query){
                    return $query->with([
                        'country' => function(ActiveQuery $query){
                            return $query->with(['currency','image']);
                        },
                        'logoImage',
                    ])->withBuyOnlinePage()->withReviewPage()->published();
                }],true,'INNER JOIN');
            }])
            ->withImage()
            ->withLanguages()
            ->withStatus(BrokerStatus::BROKER_PAGE_IMAGE)
            ->withReviewPage();
    }

    public function withReviewPage()
    {
        return $this->with([
            'reviewPage' => function(PageQuery $query){
                return $query->joinWith(['pageContentByLanguage' => function(PageContentQuery $query){
                    return $query->andWhere(['languageId' => \Yii::$app->pageData->currentLanguage->id])->published();
                }]);
            },
        ]);
    }

    public function withImage()
    {
        return $this->with('image');
    }

    public function withStatus($includingImage = BrokerStatus::LIST_IMAGE)
    {
        return $this->with(['status' => function(ActiveQuery $query) use ($includingImage) {
            return $query->with($includingImage);
        },]);
    }

    public function withLanguages()
    {
        return $this->with([
            'languages' => function(ActiveQuery $query){
                return $query->with('image');
            },
        ]);
    }

    public function orderByPositions($languageId)
    {
        return $this->addSelect('BrokerPositionToLanguage.position as orderPosition')->joinWith(['brokerPositionToLanguage' => function(ActiveQuery $query) use ($languageId) {
            return $query->onCondition(['languageId' => $languageId]);
        }])->orderBy(['BrokerPositionToLanguage.position' => SORT_ASC]);
    }
}
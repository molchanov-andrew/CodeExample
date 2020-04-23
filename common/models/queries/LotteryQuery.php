<?php
/**
 * Date: 6/28/18
 */

namespace common\models\queries;


use common\models\records\Setting;
use yii\db\ActiveQuery;

class LotteryQuery extends ActiveQuery
{
    public function selectAll()
    {
        return $this->select('Lottery.*');
    }
    public function published()
    {
        return $this->andWhere(['Lottery.published' => 1]);
    }

    public function withBrokerToLotteryCount()
    {
        return $this->joinWith('brokerToLotteries')->addSelect('COUNT(BrokerToLottery.brokerId) AS brokerToLotteryCount')->groupBy('Lottery.id');
    }

    public function withBuyOnlineData()
    {
        return $this->with([
            'brokerToLotteries' => function(ActiveQuery $query){
                return $query->joinWith(['broker' => function(BrokerQuery $query){
                    return $query->selectAll()->orderByPositions(\Yii::$app->pageData->currentLanguage->id)->with([
                        'image',
                        'bonuses',
                        'status' => function(ActiveQuery $query){
                            return $query->with('listImage');
                        },
                    ])->withReviewPage()->published();
                }],true,'INNER JOIN');
            },
        ]);
    }

    public function withLotteryTableRelatedData()
    {
        return $this->with([
            'brokerToLotteries' => function(ActiveQuery $query){
                return $query->andWhere(['brokerId' => \Yii::$app->pageData->settings[Setting::DEFAULT_BROKER_ID]])->indexBy('brokerId');
            },
            'lotteryTimers',
        ])
            ->withLogo()
            ->withReviewPage()
            ->withBuyOnlinePage()
            ->withCountryData();
    }

    public function withCountryData()
    {
        return $this->with([
            'country' => function(ActiveQuery $query){
                return $query->with(['currency','image']);
            },
        ]);
    }

    public function withLogo()
    {
        return $this->with('logoImage');
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

    public function withBuyOnlinePage()
    {
        return $this->with([
            'buyOnlinePage' => function(PageQuery $query){
                return $query->with(['pageContentByLanguage' => function(PageContentQuery $query){
                    return $query->andWhere(['languageId' => \Yii::$app->pageData->currentLanguage->id])->published();
                }]);
            },
        ]);
    }

    public function joinWithBuyOnlinePage($languageId)
    {
        return $this->joinWith(['buyOnlinePage' => function(PageQuery $query) use ($languageId) {
            return $query->joinWithCurrentLanguagePageContent();
        }],true,'INNER JOIN');
    }

    public function orderByPositions($languageId)
    {
        return $this->addSelect('LotteryPositionToLanguage.position as orderPosition')->joinWith(['lotteryPositionToLanguage' => function(ActiveQuery $query) use ($languageId) {
            return $query->onCondition(['languageId' => $languageId]);
        }])->orderBy(['Lottery.jackpot' => SORT_DESC, 'LotteryPositionToLanguage.position' => SORT_ASC]);
    }

    public function withLastResult()
    {
        return $this->with(['latestLotteryResult' => function(ActiveQuery $query){
            return $query->with('lotteryTimer');
        }]);
    }

    public function forLotteryPage()
    {
        return $this->with([
            'brokerToLotteries' => function(ActiveQuery $query){
                return $query->joinWith(['broker' => function(BrokerQuery $query){
                    return $query
                        ->with([
                            'languages' => function(ActiveQuery $query){
                                return $query->andWhere(['id' => \Yii::$app->pageData->currentLanguage->id])->with('image');
                            }
                        ])
                        ->withReviewPage()
                        ->withImage()
                        ->withStatus()
                        ->published();
                }],true,'INNER JOIN')
                    ->with([
                        'systematics',
                        'discounts',
                    ]);
            },
        ])->withBuyOnlinePage()
            ->withLogo()
            ->withCountryData()
            ->andWhere(['id' => \Yii::$app->pageData->pageContent->page->lotteryId]);
    }
}
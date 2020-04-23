<?php
/**
 * Date: 7/4/18
 */

namespace backend\modules\rest\managers;


use common\models\records\Lottery;
use common\models\records\LotteryResult;
use common\models\records\LotteryTimer;
use yii\db\ActiveQuery;

class LotteryManager implements ManagerInterface
{
    const IDENTIFIER = 'id';
    const TIMERS = 'lotteryTimers';
    const RESULTS = 'lotteryResults';
    /**
     * Getting request like :
     * [
     *      'identifier' => ex. 95,
     *      'languageIso' => ex. 'en',
     *      'data' => [
     *          'lotteryId' => [
     *              'jackpot' => 123,
     *              'lotteryTimers' => array of timers with fields,
     *              'lotteryResults' => last some count of results for current lottery
     *          ] ...
     *      ]
     * ]
     * @param $httpResponse
     * @return array
     */
    public function put($httpResponse)
    {
        $updatedTimers = 0;
        $updatedResults = 0;
        if(isset($httpResponse['data'])){
            $lotteryIds = array_keys($httpResponse['data']);
            /** @var Lottery $lottery */
            $lotteryQuery = Lottery::find()->select(['id','jackpot'])->with([
                'lotteryTimers' => function(ActiveQuery $query){
                    return $query->andWhere(['not', ['remoteId' => null]])->indexBy('remoteId');
                },
                'lotteryResults' => function(ActiveQuery $query){
                    return $query->select(['id','uniqueResultId','lotteryId'])->andWhere(['not', ['uniqueResultId' => null]])->indexBy('uniqueResultId');                }
            ]);
            $lotteries = $lotteryQuery->andWhere([self::IDENTIFIER => $lotteryIds])->all();
            if(!empty($lotteries)){
                foreach ($lotteries as $lottery) {
                    if(isset($httpResponse['data'][$lottery->id][self::TIMERS]) && !empty($httpResponse['data'][$lottery->id][self::TIMERS])){
                        foreach ($httpResponse['data'][$lottery->id][self::TIMERS] as $timer) {
                            $this->updateTimer($timer, $lottery, $updatedTimers);
                        }
                    }
                    if($updatedTimers > 0){
                        $lottery = $lotteryQuery->andWhere(['id' => $lottery->id])->one();
                    }
                    if(isset($httpResponse['data'][$lottery->id][self::RESULTS]) && !empty($httpResponse['data'][$lottery->id][self::RESULTS])){
                        foreach ($httpResponse['data'][$lottery->id][self::RESULTS] as $result) {
                            $this->addResult($result, $lottery, $updatedResults);
                        }
                    }
                }
                return ['status' => 'success', 'message' => "Lottery updated. Updated timers: {$updatedTimers}. Updated results: {$updatedResults}."];
            }
        }

        return ['status' => 'error', 'message' => 'Lotteries not updated. Wrong data format.'];
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
        /** @var Lottery[] $lotteries */
        $lotteries = Lottery::find()->select(['id','name'])->indexBy('id')->all();
        $data = [];
        foreach ($lotteries as $lottery) {
            $data[$lottery->id] = [
                'id' => $lottery->id,
                'name' => $lottery->name,
            ];
        }
        return [
            'status' => 'success',
            'data' => $data
        ];
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
        return ['status' => 'error', 'message' => 'no need to realize it for now.'];
    }

    protected function updateTimer(array $timer, Lottery $lottery, int &$counter)
    {
        if(!array_key_exists($timer['id'],$lottery->lotteryTimers)){
            $newTimer = new LotteryTimer();
            $newTimer->load($timer,'');
            $newTimer->id = null;
            $newTimer->remoteId = $timer['id'];
            $newTimer->lotteryId = $lottery->id;
            if($newTimer->save()){
                $counter++;
            }
        } else {
            $changedTimer = $lottery->lotteryTimers[$timer['id']];
            unset($timer['id']);
            $changedTimer->load($timer,'');
            if($changedTimer->oldAttributes !== $changedTimer->attributes && $changedTimer->save()){
                $counter++;
            }
        }
    }

    protected function addResult(array $result, Lottery $lottery, int &$counter)
    {
        if(!array_key_exists($result['uniqueResultId'],$lottery->lotteryResults)){
            $newResult = new LotteryResult([
                'additionalNumbers' => '',
                'bonusNumbers' => '',
            ]);
            $newResult->load($result,'');
            $newResult->lotteryId = $lottery->id;
            if(isset($result['lotteryTimerId']) && !empty($result['lotteryTimerId'])){
                if(!isset($lottery->lotteryTimers[$result['lotteryTimerId']])){
                    return;
                }
                $newResult->lotteryTimerId = $lottery->lotteryTimers[$result['lotteryTimerId']]->id;
            } else {
                $newResult->lotteryTimerId = null;
            }

            if($newResult->save()){
                $counter++;
            }
        }
    }
}
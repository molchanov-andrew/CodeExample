<?php

namespace common\models\records;

use backend\models\response\AjaxResponse;
use common\models\basic\ActiveRecord;
use DateInterval;
use DateTime;
use DateTimeZone;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "LotteryTimer".
 *
 * @property int $id
 * @property string $time
 * @property int $timeCorrection Since results are appeared not instantly - we can try to guess time we need to wait until they will appear. This time saved here in minutes.
 * @property int $dayOfWeek Day of week, 0 - Sunday, up to 6 - Saturday(alias to "w" format in php).
 * @property string $resultName Custom timer name used in future while showing results. Firstly created for lotteries having "morning"/"evening" results in a one day.
 * @property string $timezone Used for setting time including timezone(UI feature). The time in database saved in UTC timezone no matter this parameter
 * @property int $lotteryId
 * @property int $remoteId
 * @property string $created
 * @property string $updated
 *
 * Virtual fields:
 * @property DateTime|null|false $previousDatetime
 * @property DateTime|null|false $nextDatetime
 * @property LotteryResult[] $lotteryResultsYmdAsKeys
 * @property LotteryResult $lastLotteryResult
 * @property int $dayOfWeekUtc
 * Relations:
 * @property LotteryResult[] $lotteryResults
 * @property Lottery $lottery
 */
class LotteryTimer extends ActiveRecord
{
    const DIRECTION_PREVIOUS = 'datetime_previous';
    const DIRECTION_NEXT = 'datetime_next';
    const DIRECTION_CURRENT_WEEK = 'datetime_current_week';

    const DEFAULT_TIMEZONE = 'UTC';
    const HOME_TIMEZONE = 'Europe/Kiev';
    const DAYS_OF_WEEK = [
        0 => 'sunday',
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
    ];

    const TIME_FORMAT = 'H:i:s';

    /**
     * @var integer used for creating unique id for lottery result.
     */
    public $lastResultIdentifier;

    private $_previousDatetime;
    private $_nextDatetime;

    private $_lotteryResultsYmdAsKeys;

    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['timestampBehaviour'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created',
            'updatedAtAttribute' => 'updated',
            'value' => new Expression('NOW()'),
        ];
        return $behaviours;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'LotteryTimer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'dayOfWeek', 'lotteryId'], 'required'],
            [['time', 'created', 'updated'], 'safe'],
            [['timeCorrection', 'dayOfWeek', 'lotteryId', 'remoteId'], 'integer'],
            [['resultName', 'timezone'], 'string', 'max' => 255],
            [['lotteryId'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['lotteryId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Timer ID',
            'time' => 'Time',
            'timeCorrection' => 'Time Correction',
            'dayOfWeek' => 'Day Of Week',
            'resultName' => 'Result Name',
            'timezone' => 'Timezone',
            'lotteryId' => 'Lottery ID',
            'remoteId' => 'Remote ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryResults()
    {
        return $this->hasMany(LotteryResult::class, ['lotteryTimerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLottery()
    {
        return $this->hasOne(Lottery::class, ['id' => 'lotteryId']);
    }

    public function getLastLotteryResult()
    {
        return $this->hasOne(LotteryResult::class,['lotteryTimerId' => 'id'])->orderBy('date DESC');
    }

    /**
     * @param string $direction - direction to find datetime. Bounces to next\previous week if needed. Not bounces if set self::DIRECTION_CURRENT_WEEK.
     * @param array $params special options:
     * - useOwnTimezone - use timezone of timer, if not set - used timezone UTC.
     * - includeTimeCorrection - include time correction of timer to datetime.
     * - customDatetime - the datetime(mostly week of datetime) to get datetime from.
     *                  CAUTION: for securing logic of method we check the timezone of datetime,
     *                           we ignore it and getting it as string, then creating new datetime from it with timezone of timer.
     * @return bool|DateTime
     * @throws \Exception
     */
    public function getDatetimeOfTimer($direction = self::DIRECTION_PREVIOUS, array $params = [])
    {
        // Parsing params.
        $useOwnTimezone = isset($params['useOwnTimezone']) ? (bool)$params['useOwnTimezone'] : false;
        $includeTimeCorrection = isset($params['includeTimeCorrection']) ? (bool)$params['includeTimeCorrection'] : false;
        if(isset($params['customDatetime']) && $params['customDatetime'] instanceof DateTime){
            /** @var DateTime $customDatetime */
            $customDatetime = $params['customDatetime'];
            $datetimeGettingFrom = DateTime::createFromFormat('Y-m-d H:i:s',$customDatetime->format('Y-m-d H:i:s'),new DateTimeZone($this->timezone));
        } else {
            $datetimeGettingFrom = new DateTime('now',new DateTimeZone($this->timezone));
        }

        $datetimeCurrent = clone $datetimeGettingFrom;
        // Time for current Datetime add's for checking if it's a same day but time is different.
        // Ex. we getting previous timer in 8:00, but he has time 10:00. So the previous timer's datetime is actually from last week. But 2 hours ago it could be today.
        $timeArr = $this->getTimeArray();
        $datetimeCurrent->modify(self::DAYS_OF_WEEK[$this->dayOfWeek] . ' this week')->setTime(...$timeArr);
        if($includeTimeCorrection && $this->timeCorrection !== null && (int)$this->timeCorrection !== 0){
            $datetimeCurrent->add(new DateInterval('PT' . (int)$this->timeCorrection . 'M'));
        }

        if ($datetimeCurrent >= $datetimeGettingFrom && $direction === self::DIRECTION_PREVIOUS) {
            $datetimeCurrent->modify('-7 days');
        } elseif($datetimeCurrent <= $datetimeGettingFrom && $direction === self::DIRECTION_NEXT) {
            $datetimeCurrent->modify('+7 days');
        }
        // elseif ($direction === self::DIRECTION_CURRENT_WEEK) - we not doing anything.
        if(!$useOwnTimezone){
            $datetimeCurrent->setTimezone(new DateTimeZone(self::DEFAULT_TIMEZONE));
        }
        return $datetimeCurrent;
    }

    public static function getAvailableTimezones(){
        $timezones = array_combine(DateTimeZone::listIdentifiers(),DateTimeZone::listIdentifiers());
        $customTimezones = [
            'America/New_York' => 'USA Eastern',
            'America/Chicago' => 'USA Central',
            'America/Denver' => 'USA Mountain',
            'America/Phoenix' => 'USA Mountain no DST',
            'America/Los_Angeles' => 'USA Pacific',
            'America/Anchorage' => 'USA Alaska',
            'America/Adak' => 'USA Hawaii',
            'Australia/Brisbane' => 'Australian Eastern Standard Time (AEST)',
            'Australia/Sydney' => 'Australian Eastern Daylight Time (AEDT)',
            'Australia/Adelaide' => 'Australian Central Daylight Time (ACDT)',
            'Australia/Darwin' => 'Australian Central Standard Time (ACST)',
            'Australia/Perth' => 'Australian Western Standard Time (AWST)',
        ];
        return array_merge($timezones,$customTimezones);
    }

    /**
     * @return DateTime|false
     * @throws \Exception
     */
    public function getPreviousDatetime()
    {
        if(null === $this->_previousDatetime){
            $this->_previousDatetime = $this->getDatetimeOfTimer(self::DIRECTION_PREVIOUS);
        }
        return $this->_previousDatetime;
    }

    /**
     * @param DateTime $previousDatetime
     */
    public function setPreviousDatetime($previousDatetime)
    {
        $this->_previousDatetime = $previousDatetime;
    }

    /**
     * @return DateTime|false
     * @throws \Exception
     */
    public function getNextDatetime()
    {
        if(null === $this->_nextDatetime){
            $this->_nextDatetime = $this->getDatetimeOfTimer(self::DIRECTION_NEXT);
        }
        return $this->_nextDatetime;
    }

    /**
     * @param DateTime $nextDatetime
     */
    public function setNextDatetime($nextDatetime)
    {
        $this->_nextDatetime = $nextDatetime;
    }

    /**
     *  In creating i've made multiple select, and there ill handle it and create timer for each dayOfWeek
     */
    public static function saveMultiple():bool
    {
        $request = Yii::$app->request;
        $daysOfWeek = $request->post('LotteryTimer')['dayOfWeek'];
        foreach ($daysOfWeek as $dayOfWeek) {
            $model = new self();
            $model->load($request->post());
            $model->dayOfWeek = $dayOfWeek;
            if(!$model->save()){
                return false;
            }
        }
        return true;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    public function getTimeArray():array
    {
        return explode(':',$this->time);
    }

    public function getTimeUtc():string {
        if($this->timezone !== self::DEFAULT_TIMEZONE){
            $datetime = new DateTime('now',new DateTimeZone($this->timezone));
            $timeArray = $this->getTimeArray();
            $datetime->setTime(...$timeArray);
            $datetime->setTimezone(new DateTimeZone(self::DEFAULT_TIMEZONE));
            return $datetime->format('H:i:s');
        }
        return $this->time;
    }

    /**
     * Use it carefully for not losing actual date.
     * @return array
     */
    public function getTimeArrayUtc():array
    {
        $timeUtc = $this->getTimeUtc();
        return explode(':',$timeUtc);
    }

    /**
     * @throws \Exception
     */
    public function getDayOfWeekUtc(): int
    {
        return (int)$this->getDatetimeOfTimer(self::DIRECTION_CURRENT_WEEK)->format('w');
    }

    /**
     * @param string $direction
     * @return bool|DateTime
     * @throws \Exception
     */
    public function getHomeDatetimeWithTimeCorrection($direction = self::DIRECTION_PREVIOUS)
    {
        $datetime = $this->getDatetimeOfTimer($direction,[
            'useOwnTimezone' => false,
            'includeTimeCorrection' => true
        ]);
        $datetime->setTimezone(new DateTimeZone(self::HOME_TIMEZONE));
        return $datetime;
    }

    /**
     *  Returns lotteryResults with dates "Y-m-d" as keys.
     *  Used for checking does result exist when creating not loaded results in method Lottery::createResultsForPeriod().
     */
    public function getLotteryResultsYmdAsKeys(): array
    {
        if($this->_lotteryResultsYmdAsKeys === null){
            $this->_lotteryResultsYmdAsKeys = [];
            if(!empty($this->lotteryResults)){
                foreach ($this->lotteryResults as $lotteryResult) {
                    $key = $lotteryResult->getNativeDatetime()->format('Y-m-d');
                    $this->_lotteryResultsYmdAsKeys[$key] = $lotteryResult;
                }
            }
        }
        return $this->_lotteryResultsYmdAsKeys;
    }

    public static function changeMultiple($data) :AjaxResponse
    {
        if(!isset($data['rows']) || empty($data['rows'])){
            return new AjaxResponse(['status' => 'error', 'message' => 'No chosen items.']);
        }
        $rows = explode(',',$data['rows']);
        $summary = count($rows);
        $changedCount = 0;
        $models = static::find()->andWhere(['id' => $rows])->all();
        foreach ($models as $model) {
            $model->load($data);
            if($model->save()){
                $changedCount++;
            }
        }
        return new AjaxResponse(['message' => "Items changed: {$changedCount} of {$summary}"]);
    }
    public function beforeDelete()
    {
        if(!empty($this->lotteryResults)){
            foreach ($this->lotteryResults as $lotteryResult) {
                $this->unlink('lotteryResults',$lotteryResult,false);
            }
        }
        return parent::beforeDelete();
    }
}

<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use common\models\queries\LotteryResultQuery;
use common\models\records\result\ResultPage;
use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "LotteryResult".
 *
 * @property int $id
 * @property string $uniqueResultId
 * @property string $mainNumbers Numbers separated by comma.
 * @property string $additionalNumbers Numbers separated by comma.
 * @property string $bonusNumbers Numbers separated by comma.
 * @property int $status
 * @property string $date
 * @property double $jackpot
 * @property string $created
 * @property string $updated
 * @property int $lotteryId  TODO:thinking about deleting this field. Relation already established by timer.
 * @property int $lotteryTimerId
 *
 * @property Page $page
 *
 * @property Lottery $lottery
 * @property LotteryTimer $lotteryTimer
 */
class LotteryResult extends ActiveRecord
{
    const STATUS_NOT_LOADED = -1;
    const STATUS_WAITING_TO_LOAD = 0;
    const STATUS_LOADED = 1;
    const STATUSES = [
        self::STATUS_NOT_LOADED => 'Not loaded',
        self::STATUS_WAITING_TO_LOAD => 'Waiting to load',
        self::STATUS_LOADED => 'Loaded',
    ];

    const RESULTS_SEPARATOR = ',';

    protected $_page;

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

    public static function find()
    {
        return new LotteryResultQuery(static::class);
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'LotteryResult';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uniqueResultId', 'mainNumbers', 'status', 'date'], 'required'],
            [['status', 'lotteryId', 'lotteryTimerId'], 'integer'],
            [['date', 'created', 'updated'], 'safe'],
            [['jackpot'], 'number'],
            [['uniqueResultId'], 'string', 'max' => 100],
            [['mainNumbers', 'additionalNumbers', 'bonusNumbers'], 'string', 'max' => 255],
            [['uniqueResultId'], 'unique'],
            [['lotteryId'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['lotteryId' => 'id']],
            [['lotteryTimerId'], 'exist', 'skipOnError' => true, 'targetClass' => LotteryTimer::class, 'targetAttribute' => ['lotteryTimerId' => 'id']],
            [['additionalNumbers', 'bonusNumbers'],'default','value' => ''],
            ['jackpot','default','value' => 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uniqueResultId' => 'Unique Result ID',
            'mainNumbers' => 'Main Numbers',
            'additionalNumbers' => 'Additional Numbers',
            'bonusNumbers' => 'Bonus Numbers',
            'status' => 'Status',
            'date' => 'Date',
            'jackpot' => 'Jackpot',
            'created' => 'Created',
            'updated' => 'Updated',
            'lotteryId' => 'Lottery ID',
            'lotteryTimerId' => 'Lottery Timer ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLottery()
    {
        return $this->hasOne(Lottery::class, ['id' => 'lotteryId'])->inverseOf('lotteryResults');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryTimer()
    {
        return $this->hasOne(LotteryTimer::class, ['id' => 'lotteryTimerId']);
    }

    public function getCurrentDatetime()
    {
        return new DateTime($this->date,new \DateTimeZone(LotteryTimer::DEFAULT_TIMEZONE));
    }

    public function getNativeDatetime()
    {
        $datetime = $this->getCurrentDatetime();
        if($this->lotteryTimer !== null && $this->lotteryTimer->timezone !== LotteryTimer::DEFAULT_TIMEZONE){
            $datetime->setTimezone(new \DateTimeZone($this->lotteryTimer->timezone));
        }
        return $datetime;
    }

    /**
     * @return LotteryResult|null|\yii\db\ActiveRecord
     * @throws \Exception
     */
    public function getResultWithSameUniqueId(){
        if(empty($this->uniqueResultId)){
            throw new \RuntimeException('Unable to get result. Unique result id must be filled.');
        }
        return static::find()->andWhere(['uniqueResultId' => $this->uniqueResultId])->one();
    }

    public function getNumbersString(): string
    {
        return "m:{$this->mainNumbers}|a:{$this->additionalNumbers}|b:{$this->bonusNumbers}";
    }

    public function isLoaded(): bool
    {
        return $this->status === self::STATUS_LOADED;
    }

    protected function _generatePage($language = null)
    {
        $this->_page = new ResultPage();
        $this->_page->setParentResult($this);
        $this->_page->generateContentPages($language);
    }

    public function getPage()
    {
        if($this->_page === null){
            $this->_generatePage();
        }

        return $this->_page;
    }

    public function setPage($page)
    {
        $this->_page = $page;
    }
}

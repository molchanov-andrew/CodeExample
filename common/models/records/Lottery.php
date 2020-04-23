<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use common\models\queries\LotteryQuery;
use PHPUnit\Framework\Constraint\Count;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Json;

/**
 * This is the model class for table "Lottery".
 *
 * @property int $id
 * @property int $published
 * @property string $name
 * @property double $cost
 * @property double $jackpot
 * @property string $mainNumbers
 * @property string $mainNumbersToCheck
 * @property string $mainNumbersDescription
 * @property string $addNumbers
 * @property string $addNumbersToCheck
 * @property string $addNumbersDescription
 * @property string $chanceToWin
 * @property int $overallChance
 * @property string $numberAmounts Noted amount of each type of numbers in a case API provider not dividing them. Starting format is main,additional,bonus, ex. "6,,1" - means lottery has 6 main, 0 additional and 1 bonus numbers.
 * @property int $logoImageId
 * @property int $countryId
 * @property int $parentLotteryId For making complex lottery
 * @property string $created
 * @property string $updated
 *
 * Virtual fields:
 * @property LotteryTimer $closestPreviousTimer
 * @property LotteryResult $latestLotteryResult
 * @property $mainNumbersAmount
 * @property $additionalNumbersAmount
 * @property $bonusNumbersAmount
 * @property int $nextDraw
 *
 * Relations:
 * @property Country $country
 * @property Page[] $pages
 * @property Page $reviewPage
 * @property Page $buyOnlinePage
 * @property BrokerToLottery[] $brokerToLotteries
 * @property integer $brokerToLotteriesCount
 * @property Lottery $parentLottery
 * @property Lottery[] $lotteries
 * @property Image $logoImage
 * @property LotteryPositionToLanguage $lotteryPositionToLanguage
 * @property LotteryPositionToLanguage[] $lotteryPositionToLanguages
 * @property Language[] $languages
 * @property LotteryResult[] $lotteryResults
 * @property LotteryTimer[] $lotteryTimers
 * @property Json $lotteriesIdList
 *
 * @var string $lotteryImage
 */
class Lottery extends ActiveRecord
{
    const DIRECTION_PREVIOUS = 'timer_previous';
    const DIRECTION_NEXT = 'timer_next';
    const CATEGORY = 'lotteries';
    const AMOUNTS_DELIMETER = ',';

    public $brokerToLotteryCount;
    public $amounts;
    public $lotteriesList;
    public $lotteryImage;
    private $_closestPreviousTimer;

    private $_mainNumbersAmount;
    private $_additionalNumbersAmount;
    private $_bonusNumbersAmount;

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
        return 'Lottery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['published', 'jackpot', 'cost', 'logoImageId', 'countryId'], 'required'],
            [['published', 'overallChance', 'logoImageId', 'parentLotteryId', 'countryId', 'systematic'], 'integer'],
            [['jackpot', 'cost'], 'number'],
            [['created', 'updated', 'numberAmounts'], 'safe'],
            [['name'], 'unique'],
            [['name'], 'string', 'max' => 50],
            [['mainNumbers', 'mainNumbersToCheck', 'mainNumbersDescription', 'addNumbers', 'addNumbersToCheck', 'addNumbersDescription'], 'string', 'max' => 255],
            [['chanceToWin'], 'string', 'max' => 32],
            [['parentLotteryId'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['parentLotteryId' => 'id']],
            [['logoImageId'], 'exist', 'skipOnError' => true, 'targetClass' => Image::class, 'targetAttribute' => ['logoImageId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'published' => 'Published',
            'name' => 'Name',
            'jackpot' => 'Jackpot',
            'cost' => 'Cost',
            'mainNumbers' => 'Main Numbers',
            'mainNumbersToCheck' => 'Main Numbers To Check',
            'mainNumbersDescription' => 'Main Numbers Description',
            'addNumbers' => 'Add Numbers',
            'addNumbersToCheck' => 'Add Numbers To Check',
            'addNumbersDescription' => 'Add Numbers Description',
            'chanceToWin' => 'Chance To Win',
            'overallChance' => 'Overall Chance',
            'numberAmounts' => 'Number Amounts',
            'logoImageId' => 'Logo Image ID',
            'parentLotteryId' => 'Parent Lottery ID',
            'countryId' => 'Country ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    public static function find()
    {
        return new LotteryQuery(static::class);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerToLotteries()
    {
        return $this->hasMany(BrokerToLottery::class, ['lotteryId' => 'id']);
    }

    public function getBrokerToLotteriesCount()
    {
        return $this->hasMany(BrokerToLottery::class, ['lotteryId' => 'id'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentLottery()
    {
        return $this->hasOne(Lottery::class, ['id' => 'parentLotteryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteries()
    {
        return $this->hasMany(Lottery::class, ['parentLotteryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogoImage()
    {
        return $this->hasOne(Image::class, ['id' => 'logoImageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryPositionToLanguage()
    {
        return $this->hasOne(LotteryPositionToLanguage::class, ['lotteryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryPositionToLanguages()
    {
        return $this->hasMany(LotteryPositionToLanguage::class, ['lotteryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::class, ['id' => 'languageId'])->viaTable('LotteryPositionToLanguage', ['lotteryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryResults()
    {
        return $this->hasMany(LotteryResult::class, ['lotteryId' => 'id'])->inverseOf('lottery');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::class, ['lotteryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryTimers()
    {
        return $this->hasMany(LotteryTimer::class, ['lotteryId' => 'id']);
    }

    public function getLatestLotteryResult()
    {
        return $this->hasOne(LotteryResult::class, ['lotteryId' => 'id'])->orderBy('date DESC');
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'countryId']);
    }

    public function getReviewPage()
    {
        return $this->hasOne(Page::class, ['lotteryId' => 'id'])->andWhere(['module' => Page::MODULE_LOTTERY]);
    }

    public function getBuyOnlinePage()
    {
        return $this->hasOne(Page::class, ['lotteryId' => 'id'])->andWhere(['module' => Page::MODULE_BUY_LOTTERY]);
    }

    /**
     * @param string $direction
     * @return LotteryTimer|bool
     * @throws \Exception
     */
    public function getClosestTimer($direction = self::DIRECTION_PREVIOUS, $includeTimeCorrection = false)
    {
        $timers = $this->lotteryTimers;
        if (empty($timers)) {
            return false;
        }
        $datetime = new \DateTime('now', new \DateTimeZone(LotteryTimer::DEFAULT_TIMEZONE));
        $timestamp = $datetime->getTimestamp();

        $intervals = [];
        if ($direction === self::DIRECTION_PREVIOUS) {
            foreach ($timers as $key => $timer) {
                $timerTimestamp = $timer->getDatetimeOfTimer(LotteryTimer::DIRECTION_PREVIOUS, [
                    'useOwnTimezone' => false,
                    'includeTimeCorrection' => $includeTimeCorrection
                ])->getTimestamp();
                $intervals[$key] = abs(
                    $timestamp - $timerTimestamp);
            }
        } elseif ($direction === self::DIRECTION_NEXT) {
            foreach ($timers as $key => $timer) {
                $timerTimestamp = $timer->getDatetimeOfTimer(LotteryTimer::DIRECTION_NEXT, [
                    'useOwnTimezone' => false,
                    'includeTimeCorrection' => $includeTimeCorrection
                ])->getTimestamp();
                $intervals[$key] = abs($timestamp - $timerTimestamp);
            }
        }

        asort($intervals);
        $closest = key($intervals);
        return $timers[$closest];
    }

    /**
     * @return LotteryTimer|bool
     * @throws \Exception
     */
    public function getClosestPreviousTimer($includeTimeCorrection = true)
    {
        if (null === $this->_closestPreviousTimer) {
            $this->_closestPreviousTimer = $this->getClosestTimer(self::DIRECTION_PREVIOUS, $includeTimeCorrection);
        }
        return $this->_closestPreviousTimer;
    }

    /**
     * @param mixed $closestPreviousTimer
     */
    public function setClosestPreviousTimer($closestPreviousTimer)
    {
        $this->_closestPreviousTimer = $closestPreviousTimer;
    }

    /**
     * @return mixed
     */
    public function getMainNumbersAmount()
    {
        if (null === $this->_mainNumbersAmount) {
            $this->_explodeAmounts();
        }
        return $this->_mainNumbersAmount;
    }

    /**
     * @return mixed
     */
    public function getAdditionalNumbersAmount()
    {
        return $this->_additionalNumbersAmount;
    }

    /**
     * @return mixed
     */
    public function getBonusNumbersAmount()
    {
        return $this->_bonusNumbersAmount;
    }

    private function _explodeAmounts()
    {
        if (!empty($this->numberAmounts) && null === $this->_mainNumbersAmount) {
            $amounts = explode(self::AMOUNTS_DELIMETER, $this->numberAmounts);
            $this->_mainNumbersAmount = $amounts[0];
            $this->_additionalNumbersAmount = $amounts[1];
            $this->_bonusNumbersAmount = $amounts[2];
        }
    }

    public function setAmounts($value)
    {
        $this->numberAmounts = implode(self::AMOUNTS_DELIMETER, [$value['main'], $value['additional'], $value['bonus']]);
    }

    public function getAmounts(): array
    {
        if (null === $this->_mainNumbersAmount) {
            $this->_explodeAmounts();
        }
        return [
            'main' => $this->_mainNumbersAmount,
            'additional' => $this->_additionalNumbersAmount,
            'bonus' => $this->_bonusNumbersAmount,
        ];
    }

    public function load($data, $formName = null): bool
    {
        if (null === $formName && isset($data['Lottery']) & isset($data['Lottery']['amounts'])) {
            $this->setAmounts($data['Lottery']['amounts']);
            $this->_explodeAmounts();
        }
        return parent::load($data, $formName);
    }

    public function getNotRelatedBrokers($excluding = [])
    {
        if (empty($this->brokerToLotteries)) {
            return Broker::find()->all();
        }
        return Broker::find()->andWhere(['not in', 'id', array_diff(array_column($this->brokerToLotteries, 'brokerId'), $excluding)])->all();
    }

    public function beforeDelete()
    {
        if (!empty($this->brokerToLotteries)) {
            foreach ($this->brokerToLotteries as $item) {
                $item->delete();
                unset($item);
            }
        }
        if (!empty($this->lotteries)) {
            foreach ($this->lotteries as $item) {
                $this->unlink('lotteries', $item, false);
                unset($item);
            }
        }
        if (!empty($this->lotteryPositionToLanguages)) {
            foreach ($this->lotteryPositionToLanguages as $item) {
                $item->delete();
                unset($item);
            }
        }
        if (!empty($this->lotteryResults)) {
            foreach ($this->lotteryResults as $item) {
                $item->delete();
                unset($item);
            }
        }
        if (!empty($this->lotteryTimers)) {
            foreach ($this->lotteryTimers as $item) {
                $item->delete();
                unset($item);
            }
        }
        if (!empty($this->pages)) {
            foreach ($this->pages as $item) {
                $this->unlink('pages', $item, false);
                unset($item);
            }
        }
        return parent::beforeDelete();
    }

    public function hasReviewPage()
    {
        return $this->reviewPage !== null && $this->reviewPage->pageContentByLanguage !== null;
    }

    public function hasBuyOnlinePage()
    {
        return $this->buyOnlinePage !== null && $this->buyOnlinePage->pageContentByLanguage !== null;
    }

    public function hasCurrency()
    {
        return $this->country !== null && $this->country->currency !== null;
    }

    public function getNextDraw()
    {
        $nextTimer = $this->getClosestTimer(\common\models\records\Lottery::DIRECTION_NEXT);
        if (empty($nextTimer)) {
            return null;
        }
        return $nextTimer->getDatetimeOfTimer(\common\models\records\LotteryTimer::DIRECTION_NEXT)->getTimestamp();
    }
}

<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use common\models\queries\BrokerQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Broker".
 *
 * @property int $id
 * @property int $published
 * @property string $name
 * @property string $site
 * @property string $year
 * @property int $clicks
 * @property int $minimalDeposit
 * @property int $disableIframe
 * @property int $syndicat
 * @property int $systematic
 * @property int $scanTicket
 * @property int $chat
 * @property double $security
 * @property double $support
 * @property double $gameplay
 * @property double $promotions
 * @property double $withdrawals
 * @property double $usability
 * @property double $gameSelection
 * @property double $discounts
 * @property int $marks
 * @property int $summaryMarks
 * @property string $created
 * @property string $updated
 * @property int $statusId
 * @property int $imageId
 *
 * @property Image $image
 * @property BrokerStatus $status
 * @property BrokerEmail[] $brokerEmails
 * @property BrokerPhone[] $brokerPhones
 * @property BrokerPositionToLanguage $brokerPositionToLanguage - Each broker can have position for each language, but usually we are interested in 1 position per broker by 1 language.
 * @property BrokerPositionToLanguage[] $brokerPositionToLanguages
 * @property Language[] $languages
 * @property Language[] $languagesByBrokerPosition
 * @property Bonus[] $bonuses
 * @property BrokerToLottery[] $brokerToLotteries
 * @property PaymentMethod[] $paymentMethods
 * @property Page $reviewPage
 * @property Page[] $promotedPages
 * @property Page[] $pages
 */
class Broker extends ActiveRecord
{
    const THELOTTER_ID = 1;

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
        return 'Broker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['published', 'name', 'statusId', 'imageId'], 'required'],
            [['published', 'clicks','syndicat', 'minimalDeposit', 'disableIframe', 'systematic', 'scanTicket', 'chat', 'marks', 'summaryMarks', 'statusId', 'imageId'], 'integer'],
            [['security', 'support', 'gameplay', 'promotions', 'withdrawals', 'usability', 'gameSelection', 'discounts'], 'number'],
            [['created', 'updated'], 'safe'],
            [['name', 'site'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['year'], 'string', 'max' => 5],
            [['clicks', 'marks', 'summaryMarks', 'minimalDeposit', ],'default','value' => 0],
            [['imageId'], 'exist', 'skipOnError' => true, 'targetClass' => Image::class, 'targetAttribute' => ['imageId' => 'id']],
            [['statusId'], 'exist', 'skipOnError' => true, 'targetClass' => BrokerStatus::class, 'targetAttribute' => ['statusId' => 'id']],
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
            'site' => 'Site',
            'year' => 'Year',
            'clicks' => 'Clicks',
            'minimalDeposit' => 'Minimal Deposit',
            'disableIframe' => 'Disable Iframe',
            'syndicat' => 'Syndicat',
            'systematic' => 'Systematic',
            'scanTicket' => 'Scan Ticket',
            'chat' => 'Chat',
            'security' => 'Security',
            'support' => 'Support',
            'gameplay' => 'Gameplay',
            'promotions' => 'Promotions',
            'withdrawals' => 'Withdrawals',
            'usability' => 'Usability',
            'gameSelection' => 'Game Selection',
            'discounts' => 'Discounts',
            'marks' => 'Marks',
            'summaryMarks' => 'Summary Marks',
            'created' => 'Created',
            'updated' => 'Updated',
            'statusId' => 'Status ID',
            'imageId' => 'Image ID',
        ];
    }

    public static function find()
    {
        return new BrokerQuery(static::class);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::class, ['id' => 'imageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(BrokerStatus::class, ['id' => 'statusId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerEmails()
    {
        return $this->hasMany(BrokerEmail::class, ['brokerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerPhones()
    {
        return $this->hasMany(BrokerPhone::class, ['brokerId' => 'id']);
    }

    public function getBrokerPositionToLanguage()
    {
        return $this->hasOne(BrokerPositionToLanguage::class, ['brokerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerPositionToLanguages()
    {
        return $this->hasMany(BrokerPositionToLanguage::class, ['brokerId' => 'id']);
    }

    public function getLanguagesByBrokerPosition()
    {
        return $this->hasMany(Language::class, ['id' => 'languageId'])->viaTable('BrokerPositionToLanguage', ['brokerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::class, ['id' => 'languageId'])->viaTable('BrokerToLanguage', ['brokerId' => 'id'])->indexBy('id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuses()
    {
        return $this->hasMany(Bonus::class, ['id' => 'bonusId'])->viaTable('BrokerToBonus', ['brokerId' => 'id'])->indexBy('id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerToLotteries()
    {
        return $this->hasMany(BrokerToLottery::class, ['brokerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods()
    {
        return $this->hasMany(PaymentMethod::class, ['id' => 'paymentMethodId'])->viaTable('BrokerToPaymentMethod', ['brokerId' => 'id'])->indexBy('id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::class, ['brokerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotedPages()
    {
        return $this->hasMany(Page::class, ['promotingBrokerId' => 'id']);
    }

    public function getNotRelatedLotteries($excluding = [])
    {
        if(empty($this->brokerToLotteries)){
            return Lottery::find()->all();
        }
        return Lottery::find()->andWhere(['not in','id',array_diff(array_column($this->brokerToLotteries,'lotteryId'),$excluding)])->all();
    }

    public function beforeDelete()
    {
        if(!empty($this->brokerEmails)){
            foreach ($this->brokerEmails as $item) {
                $item->delete();
                unset($item);
            }
        }
        if(!empty($this->brokerPhones)){
            foreach ($this->brokerPhones as $item) {
                $item->delete();
                unset($item);
            }
        }
        if(!empty($this->brokerPositionToLanguages)){
            foreach ($this->brokerPositionToLanguages as $item) {
                $item->delete();
                unset($item);
            }
        }
        if(!empty($this->brokerToLotteries)){
            foreach ($this->brokerToLotteries as $item) {
                $item->delete();
                unset($item);
            }
        }
        if(!empty($this->paymentMethods)){
            foreach ($this->paymentMethods as $item) {
                $this->unlink('paymentMethods',$item,true);
                unset($item);
            }
        }
        if(!empty($this->bonuses)){
            foreach ($this->bonuses as $item) {
                $this->unlink('bonuses',$item,true);
                unset($item);
            }
        }
        if(!empty($this->languages)){
            foreach ($this->languages as $item) {
                $this->unlink('languages',$item,true);
                unset($item);
            }
        }

        if(!empty($this->promotedPages)){
            foreach ($this->promotedPages as $item) {
                $this->unlink('promotedPages',$item,false);
                unset($item);
            }
        }
        if(!empty($this->pages)){
            foreach ($this->pages as $item) {
                $item->delete();
                unset($item);
            }
        }
        return parent::beforeDelete();
    }

    public function linkLanguage($languageId)
    {
        $this->link('languages',Language::findOne($languageId));
        return $this;
    }

    public function unlinkLanguage($languageId)
    {
        $this->unlink('languages',Language::findOne($languageId),true);
        return $this;
    }

    public function linkBonus($bonusId)
    {
        $this->link('bonuses',Bonus::findOne($bonusId));
        return $this;
    }

    public function unlinkBonus($bonusId)
    {
        $this->unlink('bonuses',Bonus::findOne($bonusId),true);
        return $this;
    }

    public function linkPaymentMethod($paymentMethodId)
    {
        $this->link('paymentMethods',PaymentMethod::findOne($paymentMethodId));
        return $this;
    }

    public function unlinkPaymentMethod($paymentMethodId)
    {
        $this->unlink('paymentMethods',PaymentMethod::findOne($paymentMethodId),true);
        return $this;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if(!$insert){
            $this->_saveLanguagesRelations();
            $this->_saveBonusesRelations();
            $this->_savePaymentMethodsRelations();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    private function _saveLanguagesRelations()
    {
        $brokerData = Yii::$app->request->post('Broker',null);
        if($brokerData !== null && array_key_exists('languages',$brokerData)){
            $languages = $brokerData['languages'] !== '' ? $brokerData['languages'] : [] ;
            $removedLanguages = array_diff(array_keys($this->languages),$languages);
            foreach ($removedLanguages as $removedLanguage) {
                $this->unlinkLanguage($removedLanguage);
            }
            $addedLanguages = array_diff($languages,array_keys($this->languages));
            foreach ($addedLanguages as $addedLanguage) {
                $this->linkLanguage($addedLanguage);
            }
        }
    }

    private function _saveBonusesRelations()
    {
        $brokerData = Yii::$app->request->post('Broker',null);
        if($brokerData !== null && array_key_exists('bonuses',$brokerData)){
            $bonuses = $brokerData['bonuses'] !== '' ? $brokerData['bonuses'] : [] ;
            $removedBonuses = array_diff(array_keys($this->bonuses),$bonuses);
            foreach ($removedBonuses as $removedBonus) {
                $this->unlinkBonus($removedBonus);
            }
            $addedBonuses = array_diff($bonuses,array_keys($this->bonuses));
            foreach ($addedBonuses as $addedBonus) {
                $this->linkBonus($addedBonus);
            }
        }
    }

    private function _savePaymentMethodsRelations()
    {
        $brokerData = Yii::$app->request->post('Broker',null);
        if($brokerData !== null && array_key_exists('paymentMethods',$brokerData)){
            $paymentMethods = $brokerData['paymentMethods'] !== '' ? $brokerData['paymentMethods'] : [] ;
            $removedPaymentMethods = array_diff(array_keys($this->paymentMethods),$paymentMethods);
            foreach ($removedPaymentMethods as $removedPaymentMethod) {
                $this->unlinkPaymentMethod($removedPaymentMethod);
            }
            $addedPaymentMethods = array_diff($paymentMethods,array_keys($this->paymentMethods));
            foreach ($addedPaymentMethods as $addedPaymentMethod) {
                $this->linkPaymentMethod($addedPaymentMethod);
            }
        }
    }

    public function isPositive()
    {
        return $this->status !== null && $this->status->isPositive();
    }

    public function getReviewPage()
    {
        return $this->hasOne(Page::class, ['brokerId' => 'id'])->andWhere(['module' => Page::MODULE_BROKER]);
    }

    public function hasReviewPage()
    {
        return $this->reviewPage !== null && $this->reviewPage->pageContentByLanguage !== null;
    }
}

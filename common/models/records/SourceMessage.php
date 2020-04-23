<?php

namespace common\models\records;

use backend\modules\translations\models\query\SourceMessageQuery;
use Yii;
use yii\di\Instance;
use yii\caching\Cache;
use yii\i18n\DbMessageSource;

/**
 * This is the model class for table "SourceMessage".
 *
 * @property int $id
 * @property string $category
 * @property string $message
 *
 * @property Message[]|null $notSavedMessages
 * @property Message[] $messages
 */
class SourceMessage extends \vintage\i18n\models\SourceMessage
{
    const CATEGORY_GENERAL = 'app/general';
    const CATEGORY_LOTTERIES = 'app/lotteries';
    const CATEGORY_BROKERS = 'app/brokers';
    const CATEGORY_BROKER_LINKS = 'app/brokerLinks';
    const CATEGORY_BROKER_STATUSES = 'app/brokerStatuses';
    const CATEGORY_BROKER_EMAILS = 'app/brokerEmails';
    const CATEGORY_BROKER_TO_LOTTERY_LINK = 'app/brokerToLotteryLink';
    const CATEGORY_LANGUAGES = 'app/languages';
    const CATEGORY_COUNTRIES = 'app/countries';
    const CATEGORY_CURRENCIES = 'app/currencies';
    const CATEGORY_PAYMENT_METHODS = 'app/paymentMethods';
    const CATEGORY_BONUSES = 'app/bonuses';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'SourceMessage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     * @return SourceMessageQuery the newly created [[SourceMessageQuery]] instance.
     */
    public static function find()
    {
        return new SourceMessageQuery(static::class);
    }

    public function getCustomMessage()
    {
        return $this->hasOne(Message::class, ['id' => 'id']);
    }

    /**
     * Init messages
     */
    public function initMessages()
    {
        $messages = [];
        foreach (Yii::$app->getI18n()->languages as $language) {
            if (!isset($this->messages[$language])) {
                $message = new Message;
                $message->language = $language;
                $messages[$language] = $message;
            }
        }
        $this->populateRelation('notSavedMessages', $messages);
    }
    public function saveMessages()
    {
        /* @var \vintage\i18n\components\I18N $i18n */
        $i18n = Yii::$app->getI18n();
        /* @var Cache $cache */
        $cache = $i18n->enableCaching ? Instance::ensure($i18n->cache, Cache::class) : null;
        /* @var Message $message */
        foreach ($this->notSavedMessages as $message) {
            $this->link('messages', $message);
            $message->save();

            if ($i18n->enableCaching) {
                $key = [
                    DbMessageSource::class,
                    $this->category,
                    $message->language,
                ];
                $cache->delete($key);
            }
        }
    }
}

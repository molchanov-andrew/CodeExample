<?php
namespace common\models\queries;

use common\models\records\Image;
use yii\db\ActiveQuery;

class ImageQuery extends ActiveQuery
{
    public function banners()
    {
        return $this->andWhere(['category' => Image::CATEGORY_BANNERS]);
    }

    public function brokers()
    {
        return $this->andWhere(['category' => Image::CATEGORY_BROKERS]);
    }

    public function brokerStatuses()
    {
        return $this->andWhere(['category' => Image::CATEGORY_BROKER_STATUSES]);
    }

    public function lotteries()
    {
        return $this->andWhere(['category' => Image::CATEGORY_LOTTERIES]);
    }

    public function pages()
    {
        return $this->andWhere(['category' => Image::CATEGORY_PAGES]);
    }

    public function sliders()
    {
        return $this->andWhere(['category' => Image::CATEGORY_SLIDERS]);
    }

    public function paymentMethods()
    {
        return $this->andWhere(['category' => Image::CATEGORY_PAYMENT_METHODS]);
    }

    public function languages()
    {
        return $this->andWhere(['category' => Image::CATEGORY_LANGUAGES]);
    }

    public function countries()
    {
        return $this->andWhere(['category' => Image::CATEGORY_COUNTRIES]);
    }

    public function others()
    {
        return $this->andWhere(['category' => Image::CATEGORY_OTHERS]);
    }
}
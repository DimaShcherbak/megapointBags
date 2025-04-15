<?php
declare(strict_types=1);

namespace SDN\Keyt\Model;

use Magento\Framework\Model\AbstractModel;
use SDN\Keyt\Api\Data\KeytInterface;

class Keyt extends AbstractModel implements KeytInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\SDN\Keyt\Model\ResourceModel\Keyt::class);
    }

    /**
     * @inheritDoc
     */
    public function getKeytId()
    {
        return $this->getData(self::KEYT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setKeytId($keytId)
    {
        return $this->setData(self::KEYT_ID, $keytId);
    }

    /**
     * @inheritDoc
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * @inheritDoc
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * @inheritDoc
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    /**
     * @inheritDoc
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * @inheritDoc
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }
}


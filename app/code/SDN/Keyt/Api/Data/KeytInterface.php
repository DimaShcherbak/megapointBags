<?php
declare(strict_types=1);

namespace SDN\Keyt\Api\Data;

interface KeytInterface
{

    const KEYT_ID = 'keyt_id';
    const NAME = 'name';
    const PRICE = 'price';
    const QTY = 'qty';
    const SKU = 'sku';

    /**
     * Get keyt_id
     * @return string|null
     */
    public function getKeytId();

    /**
     * Set keyt_id
     * @param string $keytId
     * @return \SDN\Keyt\Keyt\Api\Data\KeytInterface
     */
    public function setKeytId($keytId);

    /**
     * Get sku
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     * @param string $sku
     * @return \SDN\Keyt\Keyt\Api\Data\KeytInterface
     */
    public function setSku($sku);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \SDN\Keyt\Keyt\Api\Data\KeytInterface
     */
    public function setName($name);

    /**
     * Get qty
     * @return string|null
     */
    public function getQty();

    /**
     * Set qty
     * @param string $qty
     * @return \SDN\Keyt\Keyt\Api\Data\KeytInterface
     */
    public function setQty($qty);

    /**
     * Get price
     * @return string|null
     */
    public function getPrice();

    /**
     * Set price
     * @param string $price
     * @return \SDN\Keyt\Keyt\Api\Data\KeytInterface
     */
    public function setPrice($price);
}


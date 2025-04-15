<?php
declare(strict_types=1);

namespace SDN\Luba\Api\Data;

interface ProductInterface
{

    const NAME = 'name';
    const PRICE = 'price';
    const PRODUCT_ID = 'product_id';
    const QTY = 'qty';
    const SKU = 'sku';

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \SDN\Luba\Product\Api\Data\ProductInterface
     */
    public function setProductId($productId);

    /**
     * Get sku
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     * @param string $sku
     * @return \SDN\Luba\Product\Api\Data\ProductInterface
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
     * @return \SDN\Luba\Product\Api\Data\ProductInterface
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
     * @return \SDN\Luba\Product\Api\Data\ProductInterface
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
     * @return \SDN\Luba\Product\Api\Data\ProductInterface
     */
    public function setPrice($price);
}


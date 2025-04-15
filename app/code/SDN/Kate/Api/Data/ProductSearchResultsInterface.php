<?php
declare(strict_types=1);

namespace SDN\Kate\Api\Data;

interface ProductSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Product list.
     * @return \SDN\Kate\Api\Data\ProductInterface[]
     */
    public function getItems();

    /**
     * Set sku list.
     * @param \SDN\Kate\Api\Data\ProductInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}


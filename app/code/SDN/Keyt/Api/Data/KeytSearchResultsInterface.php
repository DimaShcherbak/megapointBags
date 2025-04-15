<?php
declare(strict_types=1);

namespace SDN\Keyt\Api\Data;

interface KeytSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Keyt list.
     * @return \SDN\Keyt\Api\Data\KeytInterface[]
     */
    public function getItems();

    /**
     * Set sku list.
     * @param \SDN\Keyt\Api\Data\KeytInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}


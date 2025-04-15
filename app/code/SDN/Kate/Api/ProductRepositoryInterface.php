<?php
declare(strict_types=1);

namespace SDN\Kate\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductRepositoryInterface
{

    /**
     * Save Product
     * @param \SDN\Kate\Api\Data\ProductInterface $product
     * @return \SDN\Kate\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \SDN\Kate\Api\Data\ProductInterface $product
    );

    /**
     * Retrieve Product
     * @param string $productId
     * @return \SDN\Kate\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($productId);

    /**
     * Retrieve Product matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SDN\Kate\Api\Data\ProductSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Product
     * @param \SDN\Kate\Api\Data\ProductInterface $product
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \SDN\Kate\Api\Data\ProductInterface $product
    );

    /**
     * Delete Product by ID
     * @param string $productId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($productId);
}


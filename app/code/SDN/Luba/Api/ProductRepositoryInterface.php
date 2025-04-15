<?php
declare(strict_types=1);

namespace SDN\Luba\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductRepositoryInterface
{

    /**
     * Save Product
     * @param \SDN\Luba\Api\Data\ProductInterface $product
     * @return \SDN\Luba\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \SDN\Luba\Api\Data\ProductInterface $product
    );

    /**
     * Retrieve Product
     * @param string $productId
     * @return \SDN\Luba\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($productId);

    /**
     * Retrieve Product matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SDN\Luba\Api\Data\ProductSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Product
     * @param \SDN\Luba\Api\Data\ProductInterface $product
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \SDN\Luba\Api\Data\ProductInterface $product
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


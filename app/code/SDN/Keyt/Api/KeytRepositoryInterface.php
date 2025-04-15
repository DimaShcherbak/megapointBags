<?php
declare(strict_types=1);

namespace SDN\Keyt\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface KeytRepositoryInterface
{

    /**
     * Save Keyt
     * @param \SDN\Keyt\Api\Data\KeytInterface $keyt
     * @return \SDN\Keyt\Api\Data\KeytInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\SDN\Keyt\Api\Data\KeytInterface $keyt);

    /**
     * Retrieve Keyt
     * @param string $keytId
     * @return \SDN\Keyt\Api\Data\KeytInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($keytId);

    /**
     * Retrieve Keyt matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SDN\Keyt\Api\Data\KeytSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Keyt
     * @param \SDN\Keyt\Api\Data\KeytInterface $keyt
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\SDN\Keyt\Api\Data\KeytInterface $keyt);

    /**
     * Delete Keyt by ID
     * @param string $keytId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($keytId);
}


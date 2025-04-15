<?php
declare(strict_types=1);

namespace SDN\Keyt\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SDN\Keyt\Api\Data\KeytInterface;
use SDN\Keyt\Api\Data\KeytInterfaceFactory;
use SDN\Keyt\Api\Data\KeytSearchResultsInterfaceFactory;
use SDN\Keyt\Api\KeytRepositoryInterface;
use SDN\Keyt\Model\ResourceModel\Keyt as ResourceKeyt;
use SDN\Keyt\Model\ResourceModel\Keyt\CollectionFactory as KeytCollectionFactory;

class KeytRepository implements KeytRepositoryInterface
{

    /**
     * @var KeytCollectionFactory
     */
    protected $keytCollectionFactory;

    /**
     * @var KeytInterfaceFactory
     */
    protected $keytFactory;

    /**
     * @var ResourceKeyt
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Keyt
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceKeyt $resource
     * @param KeytInterfaceFactory $keytFactory
     * @param KeytCollectionFactory $keytCollectionFactory
     * @param KeytSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceKeyt $resource,
        KeytInterfaceFactory $keytFactory,
        KeytCollectionFactory $keytCollectionFactory,
        KeytSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->keytFactory = $keytFactory;
        $this->keytCollectionFactory = $keytCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(KeytInterface $keyt)
    {
        try {
            $this->resource->save($keyt);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the keyt: %1',
                $exception->getMessage()
            ));
        }
        return $keyt;
    }

    /**
     * @inheritDoc
     */
    public function get($keytId)
    {
        $keyt = $this->keytFactory->create();
        $this->resource->load($keyt, $keytId);
        if (!$keyt->getId()) {
            throw new NoSuchEntityException(__('Keyt with id "%1" does not exist.', $keytId));
        }
        return $keyt;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->keytCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(KeytInterface $keyt)
    {
        try {
            $keytModel = $this->keytFactory->create();
            $this->resource->load($keytModel, $keyt->getKeytId());
            $this->resource->delete($keytModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Keyt: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($keytId)
    {
        return $this->delete($this->get($keytId));
    }
}


<?php
declare(strict_types=1);

namespace SDN\Kate\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SDN\Kate\Api\Data\ProductInterface;
use SDN\Kate\Api\Data\ProductInterfaceFactory;
use SDN\Kate\Api\Data\ProductSearchResultsInterfaceFactory;
use SDN\Kate\Api\ProductRepositoryInterface;
use SDN\Kate\Model\ResourceModel\Product as ResourceProduct;
use SDN\Kate\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class ProductRepository implements ProductRepositoryInterface
{

    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var ResourceProduct
     */
    protected $resource;

    /**
     * @var ProductInterfaceFactory
     */
    protected $productFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Product
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceProduct $resource
     * @param ProductInterfaceFactory $productFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param ProductSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceProduct $resource,
        ProductInterfaceFactory $productFactory,
        ProductCollectionFactory $productCollectionFactory,
        ProductSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->productFactory = $productFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(ProductInterface $product)
    {
        try {
            $this->resource->save($product);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the product: %1',
                $exception->getMessage()
            ));
        }
        return $product;
    }

    /**
     * @inheritDoc
     */
    public function get($productId)
    {
        $product = $this->productFactory->create();
        $this->resource->load($product, $productId);
        if (!$product->getId()) {
            throw new NoSuchEntityException(__('Product with id "%1" does not exist.', $productId));
        }
        return $product;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->productCollectionFactory->create();
        
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
    public function delete(ProductInterface $product)
    {
        try {
            $productModel = $this->productFactory->create();
            $this->resource->load($productModel, $product->getProductId());
            $this->resource->delete($productModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Product: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($productId)
    {
        return $this->delete($this->get($productId));
    }
}


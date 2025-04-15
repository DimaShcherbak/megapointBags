<?php

namespace SDN\HideBrandsFromG\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\CategoryFactory;

class ExcludeCategoryProducts
{
    protected $productCollectionFactory;
    protected $resourceConnection;
    protected $categoryRepository;
    protected $request;

    protected $_categoryFactory;

    public function __construct(
        ProductCollectionFactory    $productCollectionFactory,
        ResourceConnection          $resourceConnection,
        CategoryRepositoryInterface $categoryRepository,
        RequestInterface            $request,
        CategoryFactory             $categoryFactory

    )
    {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->resourceConnection = $resourceConnection;
        $this->categoryRepository = $categoryRepository;
        $this->request = $request;
        $this->_categoryFactory = $categoryFactory;
    }

    public function afterGetCollection(
        \Magento\Sitemap\Model\ResourceModel\Catalog\Product $subject,
                                                             $collection
    )
    {
        $category = $this->_categoryFactory->create()->load(21);
        $excludedIds = $category->getProductCollection()->getAllIds();

        foreach ($collection as $key => $product) {
            $productId = $product->getData('id');
            if (in_array($productId, $excludedIds)) {
                unset($collection[$key]);
            }
        }
        return $collection;
    }
}

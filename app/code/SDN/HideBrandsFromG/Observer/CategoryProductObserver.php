<?php
declare(strict_types=1);

namespace SDN\HideBrandsFromG\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Page\Config as LayoutFactory;
use Magento\Framework\App\Request\Http as Request;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class CategoryProductObserver implements ObserverInterface
{
    protected $request;
    protected $layoutFactory;
    protected $productCollectionFactory;

    public function __construct(
        Request               $request,
        LayoutFactory         $layoutFactory,
        ProductCollectionFactory $productCollectionFactory
    )
    {
        $this->request = $request;
        $this->layoutFactory = $layoutFactory;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function getProductsByCategoryId($categoryId)
    {
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addCategoriesFilter(['eq' => $categoryId]);
        return $productCollection;
    }

    public function execute(Observer $observer)
    {
        $currentProductId = $this->request->getParam('id');
        if (!$currentProductId) {
            return;
        }

        $products = $this->getProductsByCategoryId(21);
        foreach ($products as $product) {
            if ($product->getId() == $currentProductId) {
                $this->layoutFactory->setRobots('NOINDEX,NOFOLLOW');
                break;
            }
        }
    }
}

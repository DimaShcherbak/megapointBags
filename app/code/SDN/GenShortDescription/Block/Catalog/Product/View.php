<?php
declare(strict_types=1);

namespace SDN\GenShortDescription\Block\Catalog\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class View extends Template
{
    protected ProductRepositoryInterface $productRepository;
    private Registry $registry;
    private Category $category;

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param Registry $registry
     * @param Category $category
     */
    public function __construct(
        Context                         $context,
        ProductRepositoryInterface      $productRepository,
        Registry     $registry,
        Category $category
    )
    {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->registry = $registry;
        $this->category = $category;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    public function getCurrentProduct(): ?\Magento\Catalog\Api\Data\ProductInterface
    {
        $productId = $this->getRequest()->getParam('product_id') ?: $this->getRequest()->getParam('id');
        if (!$productId) {
            return null;
        }

        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return null;
        }

        return $product;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        $product = $this->registry->registry('current_product');
        $categories = $product->getCategoryIds(); /*will return category ids array*/
        foreach ($categories as $category) {
            $cat = $this->category->load($category);
        }
        return $cat->getName();
    }
}

<?php
declare(strict_types=1);

namespace SDN\GoogleAnalytics\Block;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Product;

class ProductCategoryCheck extends Template
{
    protected Registry $registry;
    protected CategoryFactory $categoryFactory;

    public function __construct(
        Template\Context $context,
        Registry         $registry,
        CategoryFactory  $categoryFactory,
        array            $data = []
    )
    {
        $this->registry = $registry;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isProductInCategory21(): bool
    {
        $product = $this->registry->registry('current_product');
        if (!$product instanceof Product) {
            return false;
        }

        $categoryId = 21; // ID категорії
        $category = $this->categoryFactory->create()->load($categoryId);

        return $product->getCategoryIds() && in_array($category->getId(), $product->getCategoryIds());
    }

    /**
     * @return bool
     */
    public function isShopByBrand(): bool
    {
        // Перевірка, чи HTTP_REFERER містить 'shop-by-brand'
        return isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'shop-by-brand');
    }
}

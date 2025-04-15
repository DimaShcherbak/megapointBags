<?php
declare(strict_types=1);

namespace SDN\PriceUSD\Plugin\Catalog\Model\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class AddRateUsd
{
    const  XML_PATH_PRODUCT_MARGIN = 'price/options/price_margin';
    const  XML_PATH_PRICE_USD = 'price/options/price_usd';
    const  XML_PATH_MOD_ENABLE = 'price/options/sdn_rate_enable';

    public function __construct(
        ScopeConfigInterface  $scopeConfig,
        StoreManagerInterface $storeManager
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    public function getConfigValue()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PRICE_USD,
            ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getStoreId());
    }

    public function isEnableRateUsd()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_MOD_ENABLE,
            ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getStoreId());
    }

    public function afterGetPrice(Product $subject, $result)
    {
//        $margin = $this->scopeConfig->getValue(self::XML_PATH_PRODUCT_MARGIN,
//            ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getStoreId());
//
//        if ($this->isEnableRateUsd() === '1') {
//            $specialPrice = $subject->getPriceInfo()->getPrice('special_price')->getValue();
//
//            $configValue = $this->getConfigValue();
//            if ($configValue !== null) {
//                $marginFactor = 1 + ($margin / 100);
//
//                if ($specialPrice) {
//                    $subject->setSpecialPrice($specialPrice * $configValue * $marginFactor);
//                }
//                return ($result * $configValue * $marginFactor);
//            }
//        }
        return $result;
    }
}
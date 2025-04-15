<?php
declare(strict_types=1);

namespace SDN\HideBrandsFromG\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config;

/**
 * Class Noindexfollow
 * @package SDN\HideBrandsFromG\Observer
 */
class Noindexfollow implements ObserverInterface
{
    /**
     * @var Config
     */
    protected Config $layoutFactory;

    /**
     * Noindexfollow constructor.
     * @param Config $layoutFactory
     */
    public function __construct(
        Config $layoutFactory
    )
    {
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $fullActionName = $observer->getEvent()->getFullActionName();
        $pagesToNoIndex = [
            "brand_brand_view",
            "brand_index_index",
            "customer_account_login",
            "customer_account_forgotpassword",
            "customer_section_load",
            "onestepcheckout_index_index",
            "checkout_cart_index",
            "customer_account_create"
        ];

        if (in_array($fullActionName, $pagesToNoIndex)) {
            $this->layoutFactory->setRobots('NOINDEX,NOFOLLOW');
        }
    }

}

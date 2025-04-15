<?php
namespace SDN\Cookies\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;

class CustomerLogout implements ObserverInterface
{
    protected $cookieManager;
    protected $cookieMetadataFactory;

    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    public function execute(Observer $observer)
    {
        $metadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
            ->setPath('/')
            ->setHttpOnly(false)
            ->setDuration(-1); // Удаление куки

        $this->cookieManager->setPublicCookie('logged_in', '', $metadata);
    }
}

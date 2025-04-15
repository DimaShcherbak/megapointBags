<?php

namespace SDN\HideBrand\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\Session as CustomerSession;

class RedirectObserver implements ObserverInterface
{
    protected ResponseInterface $response;
    protected UrlInterface $url;
    protected CustomerSession $customerSession;

    public function __construct(
        ResponseInterface $response,
        UrlInterface $url,
        CustomerSession $customerSession
    ) {
        $this->response = $response;
        $this->url = $url;
        $this->customerSession = $customerSession;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if (!$this->customerSession->isLoggedIn()) {
            $request = $observer->getEvent()->getRequest();
            $pathInfo = $request->getPathInfo();

            if (str_contains($pathInfo, 'shop-by-brand')) {
                $homeUrl = $this->url->getBaseUrl();
                $this->response->setRedirect($homeUrl)->sendResponse();
                exit();
            }
        }
    }
}

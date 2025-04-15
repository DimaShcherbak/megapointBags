<?php

namespace SDN\NoIndexParams\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config;

class AddNoIndexTag implements ObserverInterface
{
    /**
     * @var Config
     */
    private Config $pageConfig;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * AddNoIndexTag constructor.
     *
     * @param RequestInterface $request
     * @param Config $pageConfig
     */
    public function __construct(RequestInterface $request, Config $pageConfig)
    {
        $this->request = $request;
        $this->pageConfig = $pageConfig;
    }

    /**
     * Add noindex tag if specific parameters are found in the URL.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $parametersToCheck = [
            'factory',
            'kolichestvo_otdelenij',
            'material',
            'internal_partition',
            'color',
            'size',
            'brand',
            'model',
            'season',
            'country',
            'price',
            'discount',
            'availability'
        ];
        $requestParams = $this->request->getParams();

        foreach ($parametersToCheck as $param) {
            if (array_key_exists($param, $requestParams)) {
                $this->pageConfig->setRobots('NOINDEX,FOLLOW');
                break;
            }
        }
    }
}

<?php
declare(strict_types=1);

namespace SDN\SinhronizeProduct\Observer\Model;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
class SaveAfter implements ObserverInterface
{
    private SaveData $saveData;

    /**
     * @param SaveData $saveData
     */
    public function __construct(\SDN\SinhronizeProduct\Observer\Model\SaveData $saveData)
    {
        $this->saveData = $saveData;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $this->saveData->saveDataProduct();
    }

}


<?php


namespace Arpo\MigrationCustomer\Model\Product\Option;



class Value extends \Magento\Catalog\Model\Product\Option\Value
{

    const KEY_WEIGHT = 'menge';
    /**
     * Return weight
     *
     * @return float|int
     */
    public function getWeight()
    {

        return $this->_getData(self::KEY_WEIGHT);
    }
}

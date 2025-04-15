<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Arpo\MigrationCustomer\Model\Product;



class Option extends \Magento\Catalog\Model\Product\Option
{

    /**
     * Return weight.(dummy method - always return 0)
     *
     * @return float|int
     */
    public function getWeight()
    {
        return 0;
    }


}

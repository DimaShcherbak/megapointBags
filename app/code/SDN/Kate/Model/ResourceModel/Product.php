<?php
declare(strict_types=1);

namespace SDN\Kate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Product extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('sdn_kate_product', 'product_id');
    }
}


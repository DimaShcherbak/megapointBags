<?php
declare(strict_types=1);

namespace SDN\Luba\Model\ResourceModel\Product;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'product_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \SDN\Luba\Model\Product::class,
            \SDN\Luba\Model\ResourceModel\Product::class
        );
    }
}


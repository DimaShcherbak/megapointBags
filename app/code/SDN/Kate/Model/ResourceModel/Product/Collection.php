<?php
declare(strict_types=1);

namespace SDN\Kate\Model\ResourceModel\Product;

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
            \SDN\Kate\Model\Product::class,
            \SDN\Kate\Model\ResourceModel\Product::class
        );
    }
}


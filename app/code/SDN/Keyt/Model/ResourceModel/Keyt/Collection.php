<?php
declare(strict_types=1);

namespace SDN\Keyt\Model\ResourceModel\Keyt;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'keyt_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \SDN\Keyt\Model\Keyt::class,
            \SDN\Keyt\Model\ResourceModel\Keyt::class
        );
    }
}


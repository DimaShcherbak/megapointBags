<?php
declare(strict_types=1);

namespace SDN\Keyt\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Keyt extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('sdn_keyt_keyt', 'keyt_id');
    }
}


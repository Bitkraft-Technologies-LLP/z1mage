<?php
namespace Zineone\Z1Connector\Model\ResourceModel\ZineoneConnectorOptions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(
            'Zineone\Z1Connector\Model\ZineoneConnectorOptions',
            'Zineone\Z1Connector\Model\ResourceModel\ZineoneConnectorOptions'
        );
    }
}

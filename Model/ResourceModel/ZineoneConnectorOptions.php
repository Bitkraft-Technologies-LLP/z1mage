<?php
namespace Zineone\Z1Connector\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ZineoneConnectorOptions extends AbstractDb
{
    /**
     * Constructor
     */
    protected function _construct() {
        $this->_init('zineone_connector_options', 'id');
    }
}
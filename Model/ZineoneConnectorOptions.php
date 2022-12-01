<?php
namespace Zineone\Z1Connector\Model;

use Magento\Framework\Model\AbstractModel;

class ZineoneConnectorOptions extends AbstractModel
{
    /**
     * @var CACHE_TAG
     */
    public const CACHE_TAG = 'id';
    /**
     * Constructor
     *
     * @return any
     */
    protected function _construct()
    {
        $this->_init('Zineone\Z1Connector\Model\ResourceModel\ZineoneConnectorOptions');
    }
    /**
     * Get identities
     *
     * @return any
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId() ];
    }
}

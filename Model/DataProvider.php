<?php
namespace Zineone\Z1Connector\Model;

use Zineone\Z1Connector\Model\ResourceModel\ZineoneConnectorOptions\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var $validConfigs
     */
    private $validConfigs = ["zineone_api_key", "zineone_send_data", "zineone_logging_enabled"];
    /**
     * @var $loadedData
     */
    protected $loadedData;
    /**
     * @var $customFactory
     */
    protected $customFactory;
    /**
     * Constructor
     *
     * @param \Zineone\Z1Connector\Model\ZineoneConnectorOptionsFactory $customFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $CollectionFactory
     * @param string $meta
     * @param string $data
     */
    public function __construct(
        \Zineone\Z1Connector\Model\ZineoneConnectorOptionsFactory $customFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $CollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $CollectionFactory->create();
        $this->customFactory = $customFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
    /**
     * Get config name
     *
     * @return any
     */
    public function getData()
    {
        $models = $this->customFactory->create()->getCollection();
        $innerArr = [];
        foreach ($models as $model) {
            if (in_array($model->getData("config_name"), $this->validConfigs)) {
                $customObj = [];
                $innerArr[$model->getData("config_name") ] = $model->getData("value");
            }
        }
        $this->loadedData["config"] = $innerArr;
        return $this->loadedData;
    }
}

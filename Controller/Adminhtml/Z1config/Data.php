<?php
namespace Zineone\Z1Connector\Controller\Adminhtml\Z1config;

class Data extends \Magento\Backend\App\Action
{
    /**
     * @var $customFactory
     */
    protected $customFactory;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Zineone\Z1Connector\Model\ZineoneConnectorOptionsFactory $customFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Zineone\Z1Connector\Model\ZineoneConnectorOptionsFactory $customFactory
    ) {
        parent::__construct($context);
        $this->customFactory = $customFactory;
    }
    /**
     * Execute
     *
     * @return mixed
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->customFactory->create();
        $items = $model->getCollection();
        $loadedData = [];
        foreach ($items as $item) {
            $customObj = [];
            $customObj[$item->getData() ["config_name"]] = $item->getData() ["value"];
            $loadedData[$item->getId() ] = $customObj;
        }
        return $resultRedirect->setPath('*/*/index', ["id" => "1"]);
    }
}

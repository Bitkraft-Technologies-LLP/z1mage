<?php
namespace Zineone\Z1Connector\Controller\Adminhtml\Z1config;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var $resultPageFactory
     */
    protected $resultPageFactory;
    /**
     * @var $collection
     */
    protected $collection;
    /**
     * @var $customFactory
     */
    protected $customFactory;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Zineone\Z1Connector\Model\ZineoneConnectorOptionsFactory $customFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Zineone\Z1Connector\Model\ZineoneConnectorOptionsFactory $customFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customFactory = $customFactory;
        parent::__construct($context);
    }
    /**
     * Execute
     *
     * @return mixed
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set((__('Session AI Configurations')));
        return $resultPage;
    }
    /**
     * Get the config data
     *
     * @param string $configName
     */
    public function getConfigData($configName)
    {
        $items = $model->getCollection();
        foreach ($items as $item) {
            if ($configName == $item->getData() ["config_name"]) {
                return $item->getData() ["value"];
            }
        }
        return null;
    }
}

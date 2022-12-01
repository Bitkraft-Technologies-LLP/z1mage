<?php

namespace Zineone\Z1Connector\Controller\Adminhtml\Z1config;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var $customFactory
     */
    protected $customFactory;
    /**
     * @var $adapterFactory
     */
    protected $adapterFactory;
    /**
     * @var $uploader
     */
    protected $uploader;
    /**
     * @var $resultRedirectFactory
     */
    protected $resultRedirectFactory;

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
     * Save the zineone config key
     *
     * @return mixed
     */
    public function execute()
    {
        $dataArr = $this->getRequest()->getPostValue();
        foreach ($dataArr as $key => $value) {
            if ($key == "form_key") {
                continue;
            }
            $model = $this->customFactory->create();
            $modelData = $model->load($key, "config_name");
            if ($modelData) {
                //Update
                $this->messageManager->addSuccess(__($key . ' updated Successfully !'));
            } else {
                //Add
                $this->messageManager->addSuccess(__($key . ' added Successfully !'));
            }
            $model->setData('config_name', $key);
            $model->setData('value', $value);
            $model->save();
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', ['id' => 'config']);
    }
}

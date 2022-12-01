<?php
namespace Zineone\Z1Connector\Block;

class CustomBlock extends \Magento\Framework\View\Element\Template
{
    /**
     * @var $httpContext
     */
    protected $httpContext;
    /**
     * @var $catalogSearchData
     */
    protected $catalogSearchData;
    /**
     * @var $registry
     */
    protected $registry;
    /**
     * @var $checkoutSession
     */
    protected $checkoutSession;
    /**
     * @var $z1Utilities
     */
    protected $z1Utilities;
    /**
     * @var $collectionModel
     */
    protected $collectionModel;
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\CatalogSearch\Helper\Data $catalogSearchData
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Zineone\Z1Connector\Common\Z1Utilities $z1Utilities
     * @param \Zineone\Z1Connector\Model\ZineoneConnectorOptionsFactory $collectionModel
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\CatalogSearch\Helper\Data $catalogSearchData,
        \Magento\Framework\Registry $registry,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Zineone\Z1Connector\Common\Z1Utilities $z1Utilities,
        \Zineone\Z1Connector\Model\ZineoneConnectorOptionsFactory $collectionModel
    ) {
        $this->httpContext = $httpContext;
        $this->catalogSearchData = $catalogSearchData;
        $this->registry = $registry;
        $this->checkoutSession = $checkoutSession;
        $this->z1Utilities = $z1Utilities;
        $this->collectionModel = $collectionModel;
        parent::__construct($context);
    }
    /**
     * Get customer by id
     *
     * @return any
     */
    public function getCustomerId()
    {
        return $this->httpContext->getValue('customer_id');
    }
    /**
     * Get search query text
     *
     * @return any
     */
    public function getSearchQueryText()
    {
        return $this->catalogSearchData->getEscapedQueryText();
    }
    /**
     * Get current category
     *
     * @return any
     */
    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }
    /**
     * Get current product
     *
     * @return any
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('product');
    }
    /**
     * Get product category
     *
     * @return any
     */
    public function getCurrentProductCategory()
    {
        $product = $this->registry->registry('product');
        return $this->z1Utilities->getProductCategory($product);
    }
    /**
     * Get Current Product Original Price
     *
     * @return any
     */
    public function getCurrentProductOriginalPrice()
    {
        $product = $this->registry->registry('product');
        return $this->z1Utilities->getProductOriginalPrice($product);
    }
    /**
     * Get Current Product Special Price
     *
     * @return any
     */
    public function getCurrentProductSpecialPrice()
    {
        $product = $this->registry->registry('product');
        return $this->z1Utilities->getProductSpecialPrice($product);
    }
    /**
     * Get Current Product Special Price
     *
     * @return any
     */
    public function getOrderData()
    {
        $order = $this->checkoutSession->getLastRealOrder();
        return $order;
    }
    /**
     * Get config data
     *
     * @param string $configName
     */
    public function getConfigData($configName)
    {
        $models = $this->collectionModel->create()->getCollection();
        foreach ($models as $model) {
            if ($configName == $model->getData("config_name")) {
                return $model->getData("value");
            }
        }
        return null;
    }
}

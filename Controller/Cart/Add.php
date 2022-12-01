<?php

namespace Zineone\Z1Connector\Controller\Cart;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Add
{
    /**
     * @var $result
     */
    protected $result;
    /**
     * @var $registry
     */
    protected $registry;
    /**
     * @var $objectManager
     */
    protected $objectManager;
    /**
     * @var $checkoutSession
     */
    protected $checkoutSession;
    /**
     * @var $productRepository
     */
    protected $productRepository;
    /**
     * @var $z1Utilities
     */
    protected $z1Utilities;

    /**
     * @param \Magento\Framework\App\Request\Http $result
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Zineone\Z1Connector\Common\Z1Utilities $z1Utilities
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $result,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Zineone\Z1Connector\Common\Z1Utilities $z1Utilities
    ) {
        $this->result = $result;
        $this->registry = $registry;
        $this->objectManager = $objectManager;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->z1Utilities = $z1Utilities;
    }

    /**
     * Gets the product by id
     *
     * @param ModuleDataSetupInterface $productId
     * @return boolean
     */
    protected function getProductById($productId)
    {
        if ($productId) {
            $storeId = $this->objectManager->
            get(\Magento\Store\Model\StoreManagerInterface::class)->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Executed after the add cart
     *
     * @param \Magento\Checkout\Controller\Cart\Add $subject
     */
    public function afterExecute(\Magento\Checkout\Controller\Cart\Add $subject)
    {
        $productId = (int)$subject->getRequest()->getParam('product');
        $product = $this->getProductById($productId);
        $prodCat = $this->z1Utilities->getProductCategory($product);
        $ogPrice = $this->z1Utilities->getProductOriginalPrice($product);
        $spPrice = $this->z1Utilities->getProductSpecialPrice($product);
        $qty = $subject->getRequest()->getParam('qty');
        if(!isset($qty))
        {
            $qty = 1;
        }
        if($spPrice == false || $spPrice == "false")
        {
            $spPrice = $ogPrice;
        }
        
        $content = [
        'productId' => $productId,
        'category' => $prodCat,
        'priceOrig' => $ogPrice,
        'priceSale' => $spPrice,
        'sku' => $product->getSku(),
        'quantity' => $qty];
        $subject->getResponse()->representJson($this->objectManager->
        get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($content));
    }
}

<?php
namespace Zineone\Z1Connector\Controller\Cart;

class UpdateItemOptions
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
     * @var $cart
     */
    protected $cart;
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
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Zineone\Z1Connector\Common\Z1Utilities $z1Utilities
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $result,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Model\Cart $cart,
        \Zineone\Z1Connector\Common\Z1Utilities $z1Utilities
    ) {
        $this->result = $result;
        $this->registry = $registry;
        $this->objectManager = $objectManager;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->z1Utilities = $z1Utilities;
    }
    /**
     * Get the product by product id
     *
     * @param ModuleDataSetupInterface $productId
     * @return false
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
     * Get the product by cart item id
     *
     * @param ModuleDataSetupInterface $itemId
     * @return false
     */
    protected function getProductByCartItemId($itemId)
    {
        if ($itemId) {
            $productData = $this->objectManager->get('Magento\Quote\Model\Quote\Item::class')->load($itemId);
            return $this->getProductById($productData->getProductId());
        }
        return false;
    }
    /**
     * Executed after update cart
     *
     * @param \Magento\Checkout\Controller\Cart\UpdateItemOptions12 $subject
     */
    public function afterExecute(\Magento\Checkout\Controller\Cart\UpdateItemOptions12 $subject)
    {
        $itemId = (int)$subject->getRequest()->getParam('item_id');
        $product = $this->getProductByCartItemId($productId);
        $prodCat = $this->z1Utilities->getProductCategory($product);
        $ogPrice = $this->z1Utilities->getProductOriginalPrice($product);
        $spPrice = $this->z1Utilities->getProductSpecialPrice($product);
        $qty = $subject->getRequest()->getParam('qty');
        $content = [
        'productId' => $productId,
        'category' => $prodCat,
        'priceOrig' => $ogPrice,
        'priceSale' => $spPrice,
        'sku' => $product->getSku(),
        'quantity' => $qty];
        $subject->getResponse()->representJson($this->objectManager->
        get('Magento\Framework\Json\Helper\DataUse::class')->jsonEncode($content));
        return $subject->getResponse();
    }
}

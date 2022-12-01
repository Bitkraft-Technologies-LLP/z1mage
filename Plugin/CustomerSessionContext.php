<?php
namespace Zineone\Z1Connector\Plugin;

class CustomerSessionContext
{
    /**
     * @var $customerSession
     */
    protected $customerSession;
    /**
     * @var $httpContext
     */
    protected $httpContext;
    /**
     * @var $checkoutSession
     */
    protected $checkoutSession;
    /**
     * @var $_logger
     */
    protected $_logger;
    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Checkout\Model\Cart $cartData
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\Cart $cartData,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->customerSession = $customerSession;
        $this->httpContext = $httpContext;
        $this->checkoutSession = $checkoutSession;
        $this->_logger = $logger;
    }
    /**
     * Execute arond dispatch
     *
     * @param \Magento\Framework\App\ActionInterface $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\App\RequestInterface $request
     * @return mixed
     */
    public function aroundDispatch(
        \Magento\Framework\App\ActionInterface $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->httpContext->setValue('customer_id', $this->customerSession->getCustomerId(), false);
        $this->httpContext->setValue('customer_name', $this->customerSession->getCustomer()->getName(), false);
        $this->httpContext->setValue('customer_email', $this->customerSession->getCustomer()->getEmail(), false);
        $this->httpContext->setValue('checkout_session', $this->checkoutSession, false);
        return $proceed($request);
    }
}

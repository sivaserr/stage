<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Checkout\Block;

use Magento\OfflinePayments\Model\Cashondelivery;

/**
 * Onepage checkout block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Onepage extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    /**
     * @var bool
     */
    protected $_isScopePrivate = false;

    /**
     * @var array
     */
    protected $jsLayout;

    /**
     * @var \Magento\Checkout\Model\CompositeConfigProvider
     */
    protected $configProvider;

    /**
     * @var array|\Magento\Checkout\Block\Checkout\LayoutProcessorInterface[]
     */
    protected $layoutProcessors;

    protected $_customerSession;

    protected $_orderConfig;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param \Magento\Checkout\Model\CompositeConfigProvider $configProvider
     * @param array $layoutProcessors
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Checkout\Model\CompositeConfigProvider $configProvider,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
    \Magento\Payment\Helper\Data $paymentHelper,
    \Magento\Payment\Model\Config $paymentConfig,
        array $layoutProcessors = [],
        array $data = []
    ) {
        $this->formKey = $formKey;
        $this->_isScopePrivate = true;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->configProvider = $configProvider;
        $this->layoutProcessors = $layoutProcessors;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->_paymentHelper = $paymentHelper;
        $this->_paymentConfig = $paymentConfig;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }
        return \Zend_Json::encode($this->jsLayout);
    }

    /**
     * Retrieve form key
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * Retrieve checkout configuration
     *
     * @return array
     * @codeCoverageIgnore
     */
    public function getCheckoutConfig()
    {
        return $this->configProvider->getConfig();
    }

    /**
     * Get base url for block.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }



    public function deleteQuoteItems(){
    $checkoutSession = $this->getCheckoutSession();
    $allItems = $checkoutSession->getQuote()->getAllVisibleItems();//returns all teh items in session
    foreach ($allItems as $item) {
        $itemId = $item->getItemId();//item id of particular item
        $quoteItem=$this->getItemModel()->load($itemId);//load particular item which you want to delete by his item id
        $quoteItem->delete();//deletes the item
    }
}
public function getCheckoutSession(){
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();//instance of object manager 
    $checkoutSession = $objectManager->get('Magento\Checkout\Model\Session');//checkout session
    return $checkoutSession;
}
 
public function getItemModel(){
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();//instance of object manager
    $itemModel = $objectManager->create('Magento\Quote\Model\Quote\Item');//Quote item model to load quote item
    return $itemModel;
}




}

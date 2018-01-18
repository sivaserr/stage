<?php
/**
 * Copyright © 2016 MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Magento\Checkout\Plugin\Payment\Method\CashOnDelivery;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Backend\Model\Auth\Session as BackendSession;

use Magento\OfflinePayments\Model\Cashondelivery;


use Magento\Checkout\Model\Session;

class Available
{

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var BackendSession
     */
    protected $backendSession;
    
    protected $session;

    /**
     * @param CustomerSession $customerSession
     * @param BackendSession $backendSession
     */
    public function __construct(
        CustomerSession $customerSession,
        BackendSession $backendSession,
        Session $session
        
    ) {
        $this->customerSession = $customerSession;
        $this->backendSession = $backendSession;
        $this->_session = $session;

    }

    /**
     *
     * @param Cashondelivery $subject
     * @param $result
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterIsAvailable(Cashondelivery $subject, $result)
       {
        // Do not remove payment method for admin
       

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 


// retrieve quote items array
$items = $cart->getQuote()->getAllItems();


foreach($items as $item) {// product id 
    

 $_product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());
    $categoryIds = $_product->getCategoryIds();
    
}


if (in_array(77, $categoryIds)) //if its category is schemes
  {

    return false;

  }else{
     return $result;
  }
  
  return $result;

}




}

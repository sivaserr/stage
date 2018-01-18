<?php

namespace send\sms\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;

class check implements ObserverInterface
{
       

    protected $_redirect;
    protected $_responseFactory;
    protected $_url;
    protected $_customerSession;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
         \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $redirect
    ) {

        $this->_customerSession = $customerSession;
       $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_redirect = $redirect;
    }
    

    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
      
        $obm = \Magento\Framework\App\ObjectManager::getInstance();
    /** @var \Magento\Framework\App\Http\Context $context */
    $context = $obm->get('Magento\Framework\App\Http\Context');
    /** @var bool $isLoggedIn */
    $isLoggedIn = $context->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    if($isLoggedIn){ 
      $om = \Magento\Framework\App\ObjectManager::getInstance();
           $customerSession = $om->create('Magento\Customer\Model\Session');
           $suser_id = $customerSession->getCustomer()->getId();
    $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');	
    $connection= $this->_resources->getConnection();
    $tableName = $this->_resources->getTableName('otpp');
    $sql = "SELECT verified FROM otpp WHERE `entity_id`='$suser_id'";
    $result = $connection->fetchall($sql);
    @$otparray = $result['0']; 
    $verified = $otparray['verified'];
     $sql = "SELECT gotp FROM otpp WHERE `entity_id`='$suser_id'";
    $result = $connection->fetchall($sql);
    @$gotp = $result['0']; 
    $gotpgot = $gotp['gotp'];
     $sql = "SELECT wotp FROM otpp WHERE `entity_id`='$suser_id'";
    $result = $connection->fetchall($sql);
    @$wotp = $result['0']; 
    $getwotp = $wotp['wotp'];
     
     if($gotpgot == 0 && $verified == 0){
        /* $event = $observer->getEvent();
             $CustomRedirectionUrl = $this->_url->getUrl('otp/index/otp/');
             $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
             return $this;*/
             
              $mobile = $this->getId(); //mobile number
    $randcode = rand(100000, 999999);
    $YourAPIKey='1c3c98a2-ace2-11e7-94da-0200cd936042';
    $OTP=$randcode;
    $SentTo=$mobile;
    
    ### DO NOT Change anything below this line
    $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    $url = "https://2factor.in/API/V1/$YourAPIKey/SMS/$SentTo/$OTP"; 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL,$url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_exec($ch); 
    curl_close($ch);
    
               
    //db connection
    $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
    ->get('Magento\Framework\App\ResourceConnection');	
    $connection= $this->_resources->getConnection();
    $tableName = $this->_resources->getTableName('otpp');
    $dates = date("Y-m-d");
    $suser_id = $this->getCustomerId();
    
    //$phone = $_POST["phone"];
    //save data
    
    
    $sql = "INSERT INTO " . $tableName . "( otp, dates, entity_id,gotp) VALUES ('$randcode', '$dates', '$suser_id',1)";
    $connection->query($sql);
     }
     if($getwotp == 1 && $verified == 0){
         $event = $observer->getEvent();
             $CustomRedirectionUrl = $this->_url->getUrl('otp/index/reenter/');
             $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
             return $this;
     }
        if($verified == 0) {
          
            $event = $observer->getEvent();
             $CustomRedirectionUrl = $this->_url->getUrl('otp/index/notverified');
             $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
             return $this;
             
           /*  $CustomRedirectionUrl = $this->_url->getUrl('otp/index/reenter');
             $this->_redirect->setRedirect($CustomRedirectionUrl);*/
        }
         

    }
    }
    
        public function getCustomerId(){
    
        $obm = \Magento\Framework\App\ObjectManager::getInstance();
    /** @var \Magento\Framework\App\Http\Context $context */
    $context = $obm->get('Magento\Framework\App\Http\Context');
    /** @var bool $isLoggedIn */
    $isLoggedIn = $context->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    if($isLoggedIn){ 
      $om = \Magento\Framework\App\ObjectManager::getInstance();
           $customerSession = $om->create('Magento\Customer\Model\Session');
           $suser_id = $customerSession->getCustomer()->getId();
           return $suser_id;
           
}
}
  public function getId(){
           
    $suser_id = $this->getCustomerId();
    $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');	
    $connection= $this->_resources->getConnection();
    $tableName = $this->_resources->getTableName('customer_entity_varchar');
    $sql = "SELECT value FROM customer_entity_varchar WHERE `entity_id`='$suser_id'";
     
    $result = $connection->fetchall($sql);
    $mobarray = $result['0']; 
    $mobileNumber = $mobarray['value'];
    return $mobileNumber;

 

}
}
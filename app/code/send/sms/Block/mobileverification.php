<?php

namespace send\sms\Block;

class mobileverification extends \Magento\Framework\View\Element\Template
{
   
   protected $customerSession;
   
   protected $_redirect;
    protected $_responseFactory;
    protected $_url;

   
       public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Response\Http $redirect,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->customerSession = $customerSession;
        
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_redirect = $redirect;
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
  
public function checknumber(){
    
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
    
    
    $sql = "INSERT INTO " . $tableName . "(phone, otp, dates, entity_id,gotp) VALUES ('$mobile', '$randcode', '$dates', '$suser_id', 1)";
    $connection->query($sql);

}



public function checkotp(){
    
$uid = $this->getCustomerId();
//db connection
$this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
->get('Magento\Framework\App\ResourceConnection');	
$connection= $this->_resources->getConnection();
$tableName = $this->_resources->getTableName('otpp');
    
$ckotp = $_POST["getotp"];

$dates = date("Y-m-d");

    // SELECT DATA
/*$sql = "SELECT otp FROM otpp WHERE  'entity_id' = '$uid'";
$result = $connection->fetchall($sql); 
$cd = $result['0'];
$dc = $cd['otp'];
*/

$dc = $this->getOtp();
    
if($dc === $ckotp){
    $uid = $this->getCustomerId();
    $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');	
    $connection= $this->_resources->getConnection();
    $tableName = $this->_resources->getTableName('otpp');
    $mobile = $this->getId();
    $sql = "UPDATE `otpp` SET `verified` = 1 WHERE entity_id = '$uid'";  
    echo "<div class='success'>";
    echo "Your mobile number verified";
    echo "</div>";
    header("Location: https://rkhomeappliances.co.in/otp/index/success");
}elseif($dc != $ckotp){
    $uid = $this->getCustomerId();
    $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');	
    $connection= $this->_resources->getConnection();
    $tableName = $this->_resources->getTableName('otpp');
    $mobile = $this->getId();
 $sql = "UPDATE `otpp` SET `wotp` = 1 WHERE entity_id = '$uid'";  
}
    
$connection->query($sql); 

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

public function getOtp(){
           
    $suser_id = $this->getCustomerId();
    $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');	
    $connection= $this->_resources->getConnection();
    $tableName = $this->_resources->getTableName('otpp');
    $sql = "SELECT otp FROM otpp WHERE `entity_id`='$suser_id'";
     
    $result = $connection->fetchall($sql);
    $otparray = $result['0']; 
    $getotp = $otparray['otp'];
    return $getotp;

}

public function uRedirect(){
    $CustomRedirectionUrl = $this->_url->getUrl('otp/index/reenter/');
             $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
             return $this;
}

}
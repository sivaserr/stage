<?php



namespace PHPCuong\CustomTabCustomer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;


class myscheme extends \Magento\Framework\View\Element\Template
{
    

  /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * DataDummy constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct (
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->_isScopePrivate = true;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }


    /**
     * @return bool
     */
    public function getCustomerSession()
    {

        /** Bug in magento 2 for get session with full page cache enabled */
        return $this->_customerSession->getCustomer();
    }
    
    /*
    protected $_customerSession;
 
    
   public function __construct(
       \Magento\Framework\View\Element\Template\Context $context,
       \Magento\Customer\Model\Session $customerSession
       )
	{
		parent::__construct($context);
		$this->_customerSession = $customerSession;
	}

	public function sayHello()
	{
		return "Hello";
	} 
 
    
    public function getCustId(){
        
        if($this->_customerSession->isLoggedIn()):
            return $this->_customerSession->getCustomer()->getId();
         endif;
    }

*/

}
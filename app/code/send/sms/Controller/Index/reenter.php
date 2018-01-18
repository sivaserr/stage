<?php

namespace send\sms\Controller\Index;



class reenter extends \Magento\Framework\App\Action\Action
{


  
        
    public function execute()
    {

        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
       
    }
}
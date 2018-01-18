<?php

/**
 *
 */
namespace Post\Module\Plugin;

/**
 *
 */
class LoginPostPlugin
{

    /**
     * Change redirect after login to home instead of dashboard.
     *
     * @param \Magento\Customer\Controller\Account $subject
     * @param \Magento\Framework\Controller\Result\Redirect $result
     */
    public function afterExecute(
        \Magento\Customer\Controller\Account\LoginPost $subject,
        $result)
    {
        $result->setPath('http://test.rkhomeappliances.co.in/'); // Change this to what you want
        return $result;
    }

}
<?php

class Novus_Ecommhub_Adminhtml_OpenEcommhubController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Redirect to external link
     * http://www.magentocommerce.com/boards/v/viewthread/211781/#t329085
     */
    public function indexAction()
    {
    $url = 'https://ecommhub.com/dashboard';
        $this->getResponse()->setRedirect($url);
    }
}

?>
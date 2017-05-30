<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Form;

class Grid extends Index
{
    public function execute()
    {
        return $this->resultPageFactory->create();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }
}

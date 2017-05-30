<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Quickresponse;

class Grid extends Index
{
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::quickresponse');
    }

    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}

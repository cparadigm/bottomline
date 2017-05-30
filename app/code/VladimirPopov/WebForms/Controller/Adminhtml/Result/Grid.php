<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

class Grid extends \VladimirPopov\WebForms\Controller\Adminhtml\Index
{
    public function execute()
    {
        $this->_initForm('webform_id');
        $resultLayout = $this->resultLayoutFactory->create();
        return $resultLayout;
    }
}

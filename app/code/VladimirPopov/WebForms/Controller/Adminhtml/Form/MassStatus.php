<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Form;

use VladimirPopov\WebForms\Controller\Adminhtml\AbstractMassStatus;

class MassStatus extends AbstractMassStatus
{
    const REDIRECT_URL = 'webforms/form/index';

    const MODEL = 'VladimirPopov\WebForms\Model\Form';

    public function execute()
    {
        $this->status = (int)$this->getRequest()->getParam('status');
        return parent::execute();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }
}

<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Fieldset;

use VladimirPopov\WebForms\Controller\Adminhtml\AbstractMassStatus;

class MassStatus extends AbstractMassStatus
{
    const ID_FIELD = 'fieldsets';

    const REDIRECT_URL = 'webforms/form/edit';

    const MODEL = 'VladimirPopov\WebForms\Model\Fieldset';

    public function execute()
    {
        $this->status = (int)$this->getRequest()->getParam('status');
        $this->redirect_params = ['id' => $this->getRequest()->getParam('id'), 'active_tab' => 'fieldsets_section'];

        return parent::execute();
    }
}

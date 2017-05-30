<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Field;

use VladimirPopov\WebForms\Controller\Adminhtml\AbstractMassDuplicate;

class MassDuplicate extends AbstractMassDuplicate
{
    const ID_FIELD = 'fields';

    const REDIRECT_URL = 'webforms/form/edit';

    const MODEL = 'VladimirPopov\WebForms\Model\Field';

    public function execute()
    {
        $this->redirect_params = ['id' => $this->getRequest()->getParam('id'), 'active_tab' => 'fields_section'];
        return parent::execute();
    }
}

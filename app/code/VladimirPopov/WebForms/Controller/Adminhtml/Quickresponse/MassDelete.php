<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Quickresponse;

use VladimirPopov\WebForms\Controller\Adminhtml\AbstractMassDelete;

/**
 * Class MassDelete
 */
class MassDelete extends AbstractMassDelete
{
    const ID_FIELD = 'quickresponses';

    const REDIRECT_URL = 'webforms/quickresponse/index';

    const MODEL = 'VladimirPopov\WebForms\Model\Quickresponse';

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::quickresponse');
    }
}

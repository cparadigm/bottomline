<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Customer\Tab\Renderer;

use Magento\Customer\Controller\RegistryConstants;

class Subject extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $subject = $row->getEmailSubject();
        $title = str_replace("'", "\'", $subject);
        return <<<HTML
        <a href="javascript:Admin_JsWebFormsResultModal('{$title}','{$this->getPopupUrl($row)}')">{$subject}</a>
HTML;
    }

    public function getPopupUrl(\Magento\Framework\DataObject $row)
    {
        $resultId = $row->getId();
        $customerId = $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
        return $this->getUrl('webforms/result/popup', array('id' => $resultId, 'customer_id' => $customerId));
    }
}
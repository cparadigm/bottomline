<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result;

class Popup extends \Magento\Backend\Block\Widget\Container
{
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        if ($this->getResult()) {
            $this->buttonList->add('print', [
                'label' => __('Print'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/print', ['_current' => true]) . '\')',
            ]);
            $this->buttonList->add('edit', [
                'label' => __('Edit Result'),
                'onclick' => 'window.location.href = \'' . $this->getUrl('*/*/edit', ['id' => $this->getRequest()->getParam('id'),'webform_id' => $this->getResult()->getWebformId(), 'customer_id' => $this->getRequest()->getParam('customer_id')]) . '\'',
            ]);
            $this->buttonList->add('reply', [
                'label' => __('Reply'),
                'class' => 'primary',
                'onclick' => 'window.location.href = \'' . $this->getUrl('*/*/reply', ['id' => $this->getRequest()->getParam('id'),'webform_id' => $this->getResult()->getWebformId(), 'customer_id' => $this->getRequest()->getParam('customer_id')]) . '\'',
            ]);
        }

        return parent::_prepareLayout();
    }

    public function getResult()
    {
        return $this->_coreRegistry->registry('webforms_result');
    }
}
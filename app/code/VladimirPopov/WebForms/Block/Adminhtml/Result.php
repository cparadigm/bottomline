<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */
namespace VladimirPopov\WebForms\Block\Adminhtml;

class Result extends \Magento\Backend\Block\Widget\Container
{
    /**
     * @var string
     */
    protected $_template = 'webforms/result.phtml';

    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = [])
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Prepare button and grid
     *
     * @return \Magento\Catalog\Block\Adminhtml\Product
     */
    protected function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'add_new_result',
            'label' => __('Add Result'),
            'class' => 'primary',
            'onclick' => 'setLocation(\'' . $this->getUrl('webforms/result/new', ['webform_id' => $this->getRequest()->getParam('webform_id')]) . '\')',
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        $this->buttonList->add('edit', array(
            'label' => __('Edit Form'),
            'onclick' => 'setLocation(\'' . $this->getEditFormUrl() . '\')',
            'class' => 'edit'
        ), '-1');

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('VladimirPopov\WebForms\Block\Adminhtml\Result\Grid', 'result.grid')
        );
        return parent::_prepareLayout();
    }


    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function getEditFormUrl()
    {
        return $this->getUrl('*/form/edit', ['id' => $this->getRequest()->getParam('webform_id')]);
    }

}
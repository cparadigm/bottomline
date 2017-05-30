<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer;

class Fields extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_fieldCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \VladimirPopov\WebForms\Model\ResourceModel\Field\CollectionFactory $fieldCollectionFactory,
        array $data = []
    )
    {
        $this->_fieldCollectionFactory = $fieldCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Render the grid cell value
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $value = $this->_fieldCollectionFactory->create()->addFilter('webform_id',$row->getId())->count();

        return (int)$value;
    }
}
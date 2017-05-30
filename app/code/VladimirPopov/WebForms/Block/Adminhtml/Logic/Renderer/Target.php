<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Logic\Renderer;

class Target extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_formFactory;

    protected $_fieldFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \VladimirPopov\WebForms\Model\FormFactory $formFactory,
        \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory,
        array $data = []
    )
    {
        $this->_formFactory = $formFactory;
        $this->_fieldFactory = $fieldFactory;
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
        $field_id = $row->getFieldId();
        $field = $this->_fieldFactory->create()->load($field_id);
        $value =  $row->getData($this->getColumn()->getIndex());

        $options = array();
        $webform = $this->_formFactory->create()->setStoreId($row->getStoreId())->load($field->getWebformId());
        $fields_to_fieldsets = $webform->getFieldsToFieldsets(true);

        foreach ($fields_to_fieldsets as $fieldset_id => $fieldset) {
            $field_options = array();
            foreach ($fieldset['fields'] as $field) {
                if (in_array('field_'.$field->getId(),$value))
                    $field_options[] = $field->getName();
            }

            if($fieldset_id){
                if(in_array('fieldset_'.$fieldset_id,$value))
                    $options[]= $fieldset['name'].' ['.__('Fieldset').']';
                if(count($field_options)){
                    $options[]= '<b>'.$fieldset['name'].'</b><br>&nbsp;&nbsp;&nbsp;&nbsp;'.implode('<br>&nbsp;&nbsp;&nbsp;&nbsp;',$field_options);
                }
            } else {
                foreach($field_options as $opt){
                    $options[]= $opt;
                }
            }
        }

        return implode('<br>',$options);
    }

}
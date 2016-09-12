<?php
class EM_Productlabels_Block_Adminhtml_Element_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareLayout()
    {
        Varien_Data_Form::setElementRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_element')
        );
        Varien_Data_Form::setFieldsetRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')
        );
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('productlabels/adminhtml_element_fieldset')
        );
    }
	
	/**
     * Set Fieldset to Form
     *
     * @param array $attributes attributes that are to be added
     * @param Varien_Data_Form_Element_Fieldset $fieldset
     * @param array $exclude attributes that should be skipped
     */
    protected function _setFieldset($attributes, $fieldset, $exclude=array())
    {
        $this->_addElementTypes($fieldset);
        foreach ($attributes as $attribute) {
            /* @var $attribute Mage_Eav_Model_Entity_Attribute */
            if (!$attribute || ($attribute->hasIsVisible() && !$attribute->getIsVisible())) {
                continue;
            }
            if ( ($inputType = $attribute->getFrontend()->getInputType())
                 && !in_array($attribute->getAttributeCode(), $exclude)
                 && ('media_image' != $inputType)
                 ) {

                $fieldType      = $inputType;
                $rendererClass  = $attribute->getFrontend()->getInputRendererClass();
                if (!empty($rendererClass)) {
                    $fieldType  = $inputType . '_' . $attribute->getAttributeCode();
                    $fieldset->addType($fieldType, $rendererClass);
                }

                $element = $fieldset->addField($attribute->getAttributeCode(), $fieldType,
                    array(
                        'name'      => $attribute->getAttributeCode(),
                        'label'     => $attribute->getFrontend()->getLabel(),
                        'class'     => $attribute->getFrontend()->getClass(),
                        'required'  => $attribute->getIsRequired(),
                        'note'      => $attribute->getNote(),
                    )
                )
                ->setEntityAttribute($attribute);
				if($attribute->getAttributeCode() == 'texthtml')
					$this->addInsertVariableButton($fieldset);
                $element->setAfterElementHtml($this->_getAdditionalElementHtml($element));

                if ($inputType == 'select') {
                    $element->setValues($attribute->getSource()->getAllOptions(true, true));
                } else if ($inputType == 'multiselect') {
                    $element->setValues($attribute->getSource()->getAllOptions(false, true));
                    $element->setCanBeEmpty(true);
                } else if ($inputType == 'date') {
                    $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
                    $element->setFormat(Mage::app()->getLocale()->getDateFormatWithLongYear());
                } else if ($inputType == 'multiline') {
                    $element->setLineCount($attribute->getMultilineCount());
                }
            }
        }
    }
	
	public function addInsertVariableButton($fieldset)
	{
        /* Add Insert Varialbe Button */
        $fieldset->addField('orig_template_variables', 'hidden', array(
            'name' => 'orig_template_variables',
        ));

        $fieldset->addField('variables', 'hidden', array(
            'name' => 'variables'
        ));

        $fieldset->addField('template_variables', 'hidden', array(
            'name' => 'template_variables',
        ));

        $insertVariableButton = $this->getLayout()
            ->createBlock('adminhtml/widget_button', '', array(
                'type' => 'button',
                'label' => Mage::helper('adminhtml')->__('Insert Variable...'),
                'onclick' => 'templateControl.openVariableChooser();return false;'
            ));

        $fieldset->addField('insert_variable', 'note', array(
            'text' => $insertVariableButton->toHtml()
        ));
		return $this;
	}
}

?>

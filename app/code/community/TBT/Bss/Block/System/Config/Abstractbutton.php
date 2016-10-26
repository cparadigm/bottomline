<?php

/**
 * WDCA - Better Store Search
 *
 */

/**
 *
 * @category   TBT
 * @package    TBT_Bss
 * @author     WDCA Better Store Search Team <contact@wdca.ca>
 */
abstract class TBT_Bss_Block_System_Config_Abstractbutton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $buttonBlock = $element->getForm ()->getParent ()->getLayout ()->createBlock ( 'adminhtml/widget_button' );
        $data = $this->getButtonData ( $buttonBlock );

        $id = $element->getHtmlId ();

        $html = '<tr><td class="label"><label for="' . $id . '">' . $element->getLabel () . '</label></td>';
        // default value
        $html .= '<td>';
        $html .= $buttonBlock->setData ( $data )->toHtml ();
        $html .= '</td>';
        $html .= '</tr>';

        return $html;
    }

    // @override me.
    public abstract function getButtonData($buttonBlock);

    protected function _getDummyElement()
    {
        if (empty ( $this->_dummyElement )) {
            $this->_dummyElement = new Varien_Object ( array ('show_in_default' => 1, 'show_in_website' => 0, 'show_in_store' => 0 ) );
        }

        return $this->_dummyElement;
    }

    protected function _getFieldRenderer()
    {
        if (empty ( $this->_fieldRenderer )) {
            $this->_fieldRenderer = Mage::getBlockSingleton ( 'adminhtml/system_config_form_field' );
        }

        return $this->_fieldRenderer;
    }

}

<?php

/**
 * Conditions renderer for category/products grid rules
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */

class ProxiBlue_GiftPromo_Block_Adminhtml_Widget_Grid_Column_Renderer_Conditions extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    protected $_defaultWidth = 100;

    /**
     * Renders CSS
     *
     * @return string
     */
    public function renderCss() {
        return parent::renderCss() . ' a-left';
    }

    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row) {
        $ruleModel = Mage::getModel('giftpromo/rule');
        if ($row->getRuleId()) {
            $ruleModel->load($row->getRuleId());
        } else {
            $ruleModel->setProductId($row->getEntityId());
        }
        if($this->getColumn()->getAsHtml()){
            $html = $ruleModel->getConditions()->asString();
        } else {
            $form = new Varien_Data_Form();
            $form->setHtmlIdPrefix('rule_for_' . $row->getEntityId() . "_");
            $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
                    ->setTemplate('promo/fieldset.phtml')
                    ->setNewChildUrl($this->getUrl('*/promo_quote/newConditionHtml/form/rule_conditions_fieldset'));
            $fieldset = $form->addFieldset('conditions_fieldset', array(
                        'legend' => Mage::helper('giftpromo')->__('Conditions')
                    ))->setRenderer($renderer);

            $fieldset->addField('conditions', 'text', array(
                'name' => 'conditions',
                'label' => Mage::helper('giftpromo')->__('Conditions'),
                'title' => Mage::helper('giftpromo')->__('Conditions'),
            ))->setRule($ruleModel)->setRenderer(Mage::getBlockSingleton('rule/conditions'));

            $form->setValues($ruleModel->getData());

            $html = $form->toHtml();
            $html .= '<input type="hidden" class="input-text rule-id'
                    . '" name="rule_id"'
                    . ' value="' . $row->getRuleId() . '"/>';
        }
        return $html;
    }

}

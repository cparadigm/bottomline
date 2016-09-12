<?php

/**
 * Promo rule actions tab
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Block_Adminhtml_Promo_Rule_Edit_Tab_Actions extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    /**
     * Constructor 
     */
    public function __construct() {
        parent::__construct();
        $this->setTemplate('giftpromo/promo/actions.phtml');
    }

    /**
     * Attach the gift product grid to the layout as a child
     * @return object 
     */
    protected function _prepareLayout() {
        $this->setChild('grid', $this->getLayout()->createBlock('giftpromo/adminhtml_promo_rule_edit_tab_actions_giftpromo_grid', 'giftpromo_promo_rule_edit_tab_actions_giftpromo_grid'));
        return parent::_prepareLayout();
    }

    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel() {
        return Mage::helper('giftpromo')->__('Gift Products');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return Mage::helper('giftpromo')->__('Gift Products');
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab() {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden() {
        return false;
    }

    /**
     * Prepare the form for the actions tab
     * @return object 
     */
    protected function _prepareForm() {
        $model = Mage::registry('current_giftpromo_promo_rule');

        //$form = new Varien_Data_Form(array('id' => 'edit_form1', 'action' => $this->getData('action'), 'method' => 'post'));
        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('action_fieldset', array('legend' => Mage::helper('giftpromo')->__('Multiple Gift Options')));

        $fieldset->addField('allow_gift_selection', 'select', array(
            'label' => Mage::helper('giftpromo')->__('Allow Gift Selection'),
            'title' => Mage::helper('giftpromo')->__('Allow Gift Selection'),
            'name' => 'allow_gift_selection',
            'options' => array(
                '1' => Mage::helper('giftpromo')->__('Yes'),
                '0' => Mage::helper('giftpromo')->__('No'),
            ),
            'note' => 'If you are gifting any configurable products, this option must be set to YES'
        ));
        
        $fieldset->addField('giftpromo', 'hidden', array(
            'name' => 'giftpromo',
        ));


        $form->setValues($model->getData());

        if ($model->isReadonly()) {
            foreach ($fieldset->getElements() as $element) {
                $element->setReadonly(true, true);
            }
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }
    
    /**
     * Return JSON string of the selected products
     * @return string 
     */
    public function getProductsJson()
    {
        $model = Mage::registry('current_giftpromo_promo_rule');
        $products =array();
        parse_str($model->getGiftpromo(), $products);
        if (!empty($products)) {
            return Mage::helper('core')->jsonEncode($products);
        }
        return '{}';
    }

}

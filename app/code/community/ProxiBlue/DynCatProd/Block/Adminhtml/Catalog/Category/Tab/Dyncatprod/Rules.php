<?php

/**
 * Tab in admin category
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Block_Adminhtml_Catalog_Category_Tab_Dyncatprod_Rules
    extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Constructor
     * */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Prepare Layout
     *
     * Creates the form that will contain the rule sets
     *
     * @return void
     */
    public function _prepareLayout()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('window.rule_');

        $form->setCategory(Mage::registry('category'));

        $data = array('conditions' => $form->getCategory()->getDynamicAttributes());
        $ruleModel = Mage::getSingleton('dyncatprod/rule');
        $ruleModel->preLoadPost(
            $data,
            Mage::registry('category')
        );

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('dyncatprod/rules.phtml')
            ->setRawRuleData($form->getCategory()->getDynamicAttributes())
            ->setDebugMode(Mage::getStoreConfig('dyncatprod/debug/enabled'))
            ->setNewChildUrl($this->getUrl('*/promo_rules/newConditionHtml/form/rule_conditions_fieldset'));
        $fieldset = $form->addFieldset(
            'conditions_fieldset',
            array(
                'legend' => Mage::helper('dyncatprod')->__('Dynamically assign products to this category, if the rules below are met:')
                )
        )->setRenderer($renderer);

        $fieldset->addField(
            'conditions',
            'text',
            array(
            'name' => 'conditions',
            'label' => Mage::helper('dyncatprod')->__('Conditions'),
            'title' => Mage::helper('dyncatprod')->__('Conditions'),
            )
        )->setRule($ruleModel)->setRenderer(Mage::getBlockSingleton('rule/conditions'));

        $this->setForm($form);
    }

}

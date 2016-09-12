<?php

/**
 * The main tab in promo rules
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Block_Adminhtml_Promo_Rule_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel() {
        return Mage::helper('giftpromo')->__('Rule Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return Mage::helper('giftpromo')->__('Rule Information');
    }

    /**
     * Returns status flag about this tab can be showed or not
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
     * Prepare the form
     * @return object
     */
    protected function _prepareForm() {
        $model = Mage::registry('current_giftpromo_promo_rule');

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('giftpromo')->__('General Information'))
        );

        if ($model->getId()) {
            $fieldset->addField('rule_id', 'hidden', array(
                'name' => 'rule_id',
            ));
        }

        $fieldset->addField('rule_name', 'text', array(
            'name' => 'rule_name',
            'label' => Mage::helper('giftpromo')->__('Rule Name'),
            'title' => Mage::helper('giftpromo')->__('Rule Name'),
            'required' => true,
        ));

        $fieldset->addField('description', 'textarea', array(
            'name' => 'description',
            'label' => Mage::helper('giftpromo')->__('Description'),
            'title' => Mage::helper('giftpromo')->__('Description'),
            'style' => 'height: 100px;',
        ));

        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('giftpromo')->__('Status'),
            'title' => Mage::helper('giftpromo')->__('Status'),
            'name' => 'is_active',
            'required' => true,
            'options' => array(
                '1' => Mage::helper('giftpromo')->__('Active'),
                '0' => Mage::helper('giftpromo')->__('Inactive'),
            ),
        ));

        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        if (Mage::app()->isSingleStoreMode()) {
            $websiteId = Mage::app()->getStore(true)->getWebsiteId();
            $fieldset->addField('website_ids', 'hidden', array(
                'name' => 'website_ids[]',
                'value' => $websiteId
            ));
            $model->setWebsiteIds($websiteId);
        } else {
            if (mage::helper('giftpromo')->isPre16()) {
                $fieldset->addField('website_ids', 'multiselect', array(
                    'name' => 'website_ids[]',
                    'label' => Mage::helper('catalogrule')->__('Websites'),
                    'title' => Mage::helper('catalogrule')->__('Websites'),
                    'required' => true,
                    'values' => Mage::getSingleton('adminhtml/system_config_source_website')->toOptionArray(),
                ));
            } else {

                $field = $fieldset->addField('website_ids', 'multiselect', array(
                    'name' => 'website_ids[]',
                    'label' => Mage::helper('giftpromo')->__('Websites'),
                    'title' => Mage::helper('giftpromo')->__('Websites'),
                    'required' => true,
                    'values' => Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm()
                ));
                $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
                $field->setRenderer($renderer);
            }
        }

        $customerGroups = Mage::getResourceModel('customer/group_collection')->load()->toOptionArray();
        $found = false;

        foreach ($customerGroups as $group) {
            if ($group['value'] == 0) {
                $found = true;
            }
        }
        if (!$found) {
            array_unshift($customerGroups, array(
                'value' => 0,
                'label' => Mage::helper('giftpromo')->__('NOT LOGGED IN'))
            );
        }

        $fieldset->addField('customer_ids', 'multiselect', array(
            'name' => 'customer_ids[]',
            'label' => Mage::helper('giftpromo')->__('Customer Groups'),
            'title' => Mage::helper('giftpromo')->__('Customer Groups'),
            'required' => true,
            'values' => Mage::getResourceModel('customer/group_collection')->toOptionArray(),
        ));

        $couponTypeFiled = $fieldset->addField('coupon_type', 'select', array(
            'name' => 'coupon_type',
            'label' => Mage::helper('giftpromo')->__('Coupon'),
            'required' => true,
            'options' => Mage::getModel('giftpromo/promo_rule')->getCouponTypes(),
        ));

        $couponCodeFiled = $fieldset->addField('coupon_code', 'text', array(
            'name' => 'coupon_code',
            'label' => Mage::helper('giftpromo')->__('Coupon Code'),
            'required' => true,
        ));

        if (!mage::helper('giftpromo')->isPre16()) {
            $autoGenerationCheckbox = $fieldset->addField('use_auto_generation', 'checkbox', array(
                'name' => 'use_auto_generation',
                'label' => Mage::helper('giftpromo')->__('Use Auto Generation'),
                'note' => Mage::helper('giftpromo')->__('If you select and save the rule you will be able to generate multiple coupon codes.'),
                'onclick' => 'handleCouponsTabContentActivity(); this.value = this.checked ? 1 : 0;',
                'checked' => (int) ($model->getUseAutoGeneration() == 1) ? 'checked' : '',
                'default' => 1
            ));

            $autoGenerationCheckbox->setRenderer(
                    $this->getLayout()->createBlock('giftpromo/adminhtml_promo_rule_edit_tab_main_renderer_checkbox')
            );
        }

        $usesPerCouponFiled = $fieldset->addField('uses_per_coupon', 'text', array(
            'name' => 'uses_per_coupon',
            'label' => Mage::helper('giftpromo')->__('Uses per Coupon'),
        ));

        $fieldset->addField('uses_per_customer', 'text', array(
            'name' => 'uses_per_customer',
            'label' => Mage::helper('giftpromo')->__('Uses per Customer'),
        ));


        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('from_date', 'date', array(
            'name' => 'from_date',
            'label' => Mage::helper('giftpromo')->__('From Date'),
            'title' => Mage::helper('giftpromo')->__('From Date'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format' => $dateFormatIso
        ));
        $fieldset->addField('to_date', 'date', array(
            'name' => 'to_date',
            'label' => Mage::helper('giftpromo')->__('To Date'),
            'title' => Mage::helper('giftpromo')->__('To Date'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format' => $dateFormatIso
        ));

        $fieldset->addField('stop_rules_processing', 'select', array(
            'label' => Mage::helper('giftpromo')->__('Stop Further Gift Rules Processing'),
            'title' => Mage::helper('giftpromo')->__('Stop Further Gift Rules Processing'),
            'name' => 'stop_rules_processing',
            'options' => array(
                '1' => Mage::helper('giftpromo')->__('Yes'),
                '0' => Mage::helper('giftpromo')->__('No'),
            ),
        ));

        $fieldset->addField('sort_order', 'text', array(
            'name' => 'sort_order',
            'label' => Mage::helper('giftpromo')->__('Priority'),
        ));


        $form->setValues($model->getData());

        $this->setForm($form);

        if (!mage::helper('giftpromo')->isPre16()) {
            $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                        ->addFieldMap($couponTypeFiled->getHtmlId(), $couponTypeFiled->getName())
                        ->addFieldMap($couponCodeFiled->getHtmlId(), $couponCodeFiled->getName())
                        ->addFieldMap($autoGenerationCheckbox->getHtmlId(), $autoGenerationCheckbox->getName())
                        ->addFieldMap($usesPerCouponFiled->getHtmlId(), $usesPerCouponFiled->getName())
                        ->addFieldDependence(
                                $couponCodeFiled->getName(), $couponTypeFiled->getName(), Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
                        ->addFieldDependence(
                                $autoGenerationCheckbox->getName(), $couponTypeFiled->getName(), Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
                        ->addFieldDependence(
                                $usesPerCouponFiled->getName(), $couponTypeFiled->getName(), Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
        );
        } else {
            // field dependencies
            $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                            ->addFieldMap($couponTypeFiled->getHtmlId(), $couponTypeFiled->getName())
                            ->addFieldMap($couponCodeFiled->getHtmlId(), $couponCodeFiled->getName())
                            ->addFieldMap($usesPerCouponFiled->getHtmlId(), $usesPerCouponFiled->getName())
                            ->addFieldDependence(
                                    $couponCodeFiled->getName(), $couponTypeFiled->getName(), Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
                            ->addFieldDependence(
                                    $usesPerCouponFiled->getName(), $couponTypeFiled->getName(), Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
            );
        }

        Mage::dispatchEvent('adminhtml_giftpromo_promo_rule_edit_tab_main_prepare_form', array('form' => $form));

        return parent::_prepareForm();
    }

}

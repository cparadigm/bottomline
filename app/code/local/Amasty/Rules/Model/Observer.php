<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Rules
 */
class Amasty_Rules_Model_Observer
{
    /**
     * Process sales rule form creation
     *
     * @param   Varien_Event_Observer $observer
     * @return $this
     */
    public function handleFormCreation($observer)
    {
        $actionsSelect = $observer->getForm()->getElement('simple_action');
        if ($actionsSelect) {
            $defaultTypes = $actionsSelect->getValues();

            $defaultTypes[3]['label'] = Mage::helper('amrules')->__('Buy X qty, pay for Y qty of the same product.');
            $defaultGroupedTypes = array(
                0 => array(
                    'label' => 'Default',
                    'value' => $defaultTypes,
                )
            );

            $actionsSelect->setValues(
                array_merge(
                    $defaultGroupedTypes,
                    Mage::helper('amrules')->getDiscountTypes()
                )
            );

            $actionsSelect->setOnchange('ampromo_hide();');
        }


        $fldSet = $observer->getForm()->getElement('action_fieldset');
        if ($fldSet) {
            if ('true' != (string)Mage::getConfig()->getNode('modules/Amasty_Promo/active')) {
                $fldSet->addField(
                    'promo_sku', 'text', array(
                        'name'  => 'promo_sku',
                        'label' => Mage::helper('amrules')->__('Promo Items'),
                        'note'  => Mage::helper('amrules')->__('Comma separated list of the SKUs'),
                    ),
                    'discount_amount'
                );
            }

            $fldSet->addField(
                'promo_cats', 'text', array(
                    'name'  => 'promo_cats',
                    'label' => Mage::helper('amrules')->__('Promo Categories'),
                    'note'  => Mage::helper('amrules')->__('Comma separated list of the category ids'),
                ),
                'discount_amount'
            );

            $fldSet->addField(
                'each_m', 'text', array(
                    'name'  => 'each_m',
                    'label' => Mage::helper('amrules')->__('Each Mth product'),
                ),
                'discount_amount'
            );
            $fldSet->addField(
                'buy_x_get_n', 'text', array(
                'name'  => 'buy_x_get_n',
                'label' => Mage::helper('amrules')->__('Number of Discounted Products (Get N)'),
            ),
                'discount_amount'
            );

            $fldSet->addField(
                'max_discount', 'text', array(
                'name'  => 'max_discount',
                'label' => Mage::helper('amrules')->__('Max Amount of Discount'),
            ),
                'discount_amount'
            );


            $fldSet->addField('price_selector', 'select', array(
                'label'     => Mage::helper('amrules')->__('Calculate Discount Based On'),
                'title'     => Mage::helper('amrules')->__('Calculate Discount Based On'),
                'name'      => 'price_selector',
                'options'    => array(
                    '0' => Mage::helper('amrules')->__('Price (Special Price if Set)'),
                    '1' => Mage::helper('amrules')->__('Price After Previous Discount(s)'),
                    '2' => Mage::helper('amrules')->__('Original Price'),
                ),
            ));

            $fldSet->addField('amskip_rule', 'select', array(
                'label'     => Mage::helper('amrules')->__('Skip Items with Special Price'),
                'title'     => Mage::helper('amrules')->__('Skip Items with Special Price'),
                'name'      => 'amskip_rule',
                'options'    => array(
                    '0' => Mage::helper('amrules')->__('As Default'),
                    '1' => Mage::helper('amrules')->__('Yes'),
                    '2' => Mage::helper('amrules')->__('No'),
                    '3' => Mage::helper('amrules')->__('Skip If Discounted'),
                ),
            ));

        }


        return $this;
    }


    /**
     * @param $observer
     * Process quote item validation and discount calculation
     * @return $this
     */
    public function handleValidation($observer)
    {
        $promotions =  Mage::getSingleton('amrules/promotions');
        $promotions->process($observer);
        return $this;
    }

    /**
     * Process sales rule before save
     *
     * @param   Varien_Event_Observer $observer
     */
    public function saveBefore($observer)
    {
        $controllerAction = $observer->getRule()->getData();
        $setof_percent = Amasty_Rules_Helper_Data::TYPE_SETOF_PERCENT;
        $setof_fixed = Amasty_Rules_Helper_Data::TYPE_SETOF_FIXED;

        if ($controllerAction['simple_action'] == $setof_percent || $controllerAction['simple_action'] == $setof_fixed) {
            $data = $observer->getRule()->getData();
            $r = array(
                'type'               => 'salesrule/rule_condition_product_combine',
                'attribute'          => null,
                'operator'           => null,
                'value'              => '1',
                'is_value_processed' => null,
                'aggregator'         => 'any',
                'conditions'         =>
                    array(
                        0 =>
                            array(
                                'type'               => 'salesrule/rule_condition_product',
                                'attribute'          => 'category_ids',
                                'operator'           => '()',
                                'value'              => $data['promo_cats'],
                                'is_value_processed' => false,
                            ),
                        1 =>
                            array(
                                'type'               => 'salesrule/rule_condition_product',
                                'attribute'          => 'quote_item_sku',
                                'operator'           => '()',
                                'value'              => $data['promo_sku'],
                                'is_value_processed' => false,
                            ),
                    ),
            );
            $itemsInSet = count(preg_split('@,@', $data['promo_cats'], null, PREG_SPLIT_NO_EMPTY))
                + count(preg_split('@,@', $data['promo_sku'], null, PREG_SPLIT_NO_EMPTY));
            $data['actions_serialized'] = serialize($r);
            $data['discount_step'] = $itemsInSet;

            $observer->getRule()->setData($data);
        }
    }

    /**
     * Process sales rule after save
     *
     * @param   Varien_Event_Observer $observer
     */
    public function saveAfter($observer)
    {
        $rule = $observer->getRule();

        $conditions = $rule->getConditions()->asArray();
        $unsafeIs = 0;
        if (isset($conditions['conditions'])) {
            $unsafeIs = $this->checkForIs($conditions['conditions']);
        }

        $actions = $rule->getActions()->asArray();
        if (isset($actions['conditions']) && !$unsafeIs) {
            $unsafeIs = $this->checkForIs($actions['conditions']);
        }

        if ($unsafeIs) {
            Mage::getSingleton('adminhtml/session')->addNotice('It is more safe to use `is one of` operator and not `is` for comparison.  Please correct if the rule does not work as expected.');
        }

        if (Mage::app()->getStore()->isAdmin()) {
            $this->_saveBannersInfo($rule);
        }
    }

    /**
     * @param $rule
     */
    protected function _saveBannersInfo($rule) {
        $data = $rule->getData();
        $bannersModel = Mage::getModel('amrules/banners');
        $id = Mage::app()->getRequest()->getParam('rule_id');
        if ($id) {
            $bannersModel = $bannersModel->loadByRuleId($id);
        }
        $bannersModel->addData($data);
        $bannersModel->save();
    }

    protected function checkForIs($array)
    {
        foreach ($array as $element) {
            if ($element['operator'] == '==' && strpos($element['value'],',') !== FALSE) {
                return true;
            }
            if (isset($element['conditions'])) {
                $this->checkForIs($element['conditions']);
            }
        }
        return false;
    }

    /**
     * save images on server
     * @param $observer
     */
    public function salesRulePrepareSave($observer)
    {
        $this->_saveRulesImage($observer->getRequest(), 'top_banner_img');
        $this->_saveRulesImage($observer->getRequest(), 'after_name_banner_img');
        $this->_saveRulesImage($observer->getRequest(), 'label_img');
    }

    /**
     * @param $request
     * @param $file
     */
    protected function _saveRulesImage($request, $file)
    {
        if ($data = $request->getPost()) {
            if (isset($data[$file]) && isset($data[$file]['delete'])) {
                $data[$file] = null;
            } else {
                if (isset($_FILES[$file]['name']) && $_FILES[$file]['name'] != '') {
                    $fileName = Mage::helper("amrules/image")->upload($file);
                    $data[$file] = $fileName;
                } else {
                    if (isset($data[$file]['value'])) {
                        $data[$file] = basename($data[$file]['value']);
                    }
                }
            }

            $request->setPost($data);
        }
    }

    /**
     * add enctype = 'multipart/form-data'
     * @param $observer
     */
    public function addEnctypeToForm($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Promo_Quote_Edit_Form){
            $form = $observer->getBlock()->getForm();
            $form->setData('enctype', 'multipart/form-data');
            $form->setUseContainer(true);
        }
    }
}

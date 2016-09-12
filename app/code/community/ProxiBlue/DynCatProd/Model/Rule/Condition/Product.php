<?php

/**
 * Product rule condition data model
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Product
        extends ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Abstract
{

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_product')
            ->setProcessingOrder('any');
    }

    /**
     * Add special attributes
     *
     * @param array $attributes
     *
     * @return type Description
     */
    protected function _addSpecialAttributes(array &$attributes)
    {
        parent::_addSpecialAttributes($attributes);
    }

    /**
     * Validate Product Rule Condition
     *
     * @param Varien_Object $object
     *
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
        return parent::validate($object);
    }

    /**
     * Default operator input by type map getter
     *
     * @return array
     */
    public function getDefaultOperatorInputByType()
    {
        if (null === $this->_defaultOperatorInputByType) {
            $this->_defaultOperatorInputByType = array(
                'string' => array('{}', '!{}', '==', '!=', '>=', '>', '<=', '<', '()', '!()'),
                'numeric' => array('==', '!=', '>=', '>', '<=', '<', '()', '!()'),
                'date' => array('==', '!=', '>=', '>', '<=', '<', '<D'),
                'date_range' => array('!!', '!!!'),
                'select' => array('==', '!='),
                'boolean' => array('==', '!=', '==|', '!=|'),
                'multiselect' => array('{}', '!{}'),
                'grid' => array('()', '!()'),
                'category' => array('==', '!=', '()', '!()'),
                'applied_catalog_rule_id' => array('==', '()')
            );
            $this->_arrayInputTypes = array('multiselect', 'grid', 'category');
        }

        return $this->_defaultOperatorInputByType;
    }

    /**
     * Default operator options getter
     * Provides all possible operator options
     *
     * @return array
     */
    public function getDefaultOperatorOptions()
    {
        if (null === $this->_defaultOperatorOptions) {
            $this->_defaultOperatorOptions = array(
                '==' => Mage::helper('rule')->__('is'),
                '!=' => Mage::helper('rule')->__('is not'),
                '>=' => Mage::helper('rule')->__('equals or greater than'),
                '<=' => Mage::helper('rule')->__('equals or less than'),
                '>' => Mage::helper('rule')->__('greater than'),
                '<' => Mage::helper('rule')->__('less than'),
                '{}' => Mage::helper('rule')->__('contains'),
                '!{}' => Mage::helper('rule')->__('does not contain'),
                '()' => Mage::helper('rule')->__('is one of'),
                '!()' => Mage::helper('rule')->__('is not one of'),
                '!!' => Mage::helper('rule')->__('is within range on day of viewing'),
                '!!!' => Mage::helper('rule')->__('is not within range on day of viewing'),
                '<D' => Mage::helper('rule')->__('is X days ago on day of viewing'),
                '==|' => Mage::helper('rule')->__('is ( ignore attribute default )'),
                '!=|' => Mage::helper('rule')->__('is not ( ignore attribute default )'),
            );
        }

        return $this->_defaultOperatorOptions;
    }

    /**
     * Retrieve after element HTML
     *
     * @return string
     */
    public function getValueAfterElementHtml()
    {
        $html = '';
        switch ($this->getAttribute()) {
        case 'sku':
        case 'category_ids':
        case 'category_child':
            $image = Mage::getDesign()->getSkinUrl('images/rule_chooser_trigger.gif');
            break;
        case 'applied_catalog_rule_id':
            Mage::getDesign()->getSkinUrl('images/rule_chooser_trigger.gif');
            $html .= '<a href="javascript:void(0)" class="rule-chooser-trigger"><img src="' . Mage::getDesign()->getSkinUrl('images/rule_chooser_trigger.gif') . '" alt="" class="v-middle rule-chooser-trigger" title="' . Mage::helper('rule')->__('Open Chooser') . '" /></a>';
            $html .= ' (products will appear if the date range is valid on the day viewed) ';
            break;
        }

        if (!empty($image)) {
            $html = '<a href="javascript:void(0)" class="rule-chooser-trigger"><img src="' . $image . '" alt="" class="v-middle rule-chooser-trigger" title="' . Mage::helper('rule')->__('Open Chooser') . '" /></a>';
        }

        return $html;
    }

    public function getValueElementRenderer()
    {
        if (strpos($this->getValueElementType(), '/') !== false) {
            return Mage::getBlockSingleton($this->getValueElementType());
        }

        return Mage::getBlockSingleton('dyncatprod/rule_editable');
    }

    public function getValueName()
    {
        $value = $this->getValue();

        if (is_null($value) || '' === $value) {
            return '...';
        }

        $options = $this->getValueSelectOptions();
        $valueArr = array();
        if (!empty($options)) {
            foreach ($options as $o) {
                if (is_array($value)) {
                    if (in_array($o['value'], $value)) {
                        $valueArr[] = $o['label'];
                    }
                } else {
                    if (is_array($o['value'])) {
                        foreach ($o['value'] as $v) {
                            if ($v['value'] == $value) {
                                return $v['label'];
                            }
                        }
                    }
                    if ($o['value'] == $value) {
                        return $o['label'];
                    }
                }
            }
        }
        if (!empty($valueArr)) {
            $value = implode(', ', $valueArr);
        }

        return $value;
    }

    public function getAttributeSelectOptions()
    {
        $opt = array();
        foreach ($this->getAttributeOption() as $type => $value) {
            foreach ($value as $k => $v) {
                $opt[] = array('value' => $k, 'label' => $v);
            }
        }

        return $opt;
    }

    public function getAttributeName()
    {
        foreach ($this->getAttributeOption() as $type => $value) {
            if (array_key_exists($this->getAttribute(), $value)) {
                return $value[$this->getAttribute()];
            }
        }
    }

}

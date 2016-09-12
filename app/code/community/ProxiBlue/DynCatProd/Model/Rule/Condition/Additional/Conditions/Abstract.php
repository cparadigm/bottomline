<?php

/**
 * Abstract Rule product condition data model - does not exist in magento prior to 1.7 / 1.12
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 * */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Abstract extends ProxiBlue_DynCatProd_Model_Rule_Condition_Abstract
{

    /**
     * Load the given array into the object as rule data
     *
     * @param array  $arr
     * @param string $key
     */
    public function loadArray($arr, $key = 'conditions')
    {
        if (array_key_exists('operator', $arr)) {
            $this->setOperator($arr['operator']);
        }
        parent::loadArray($arr, $key);

        return $this;
    }

    public function loadValueOptions()
    {
        return array();
    }

    /**
     * Get this models Element Type
     * @return type
     */
    public function getValueElementType()
    {
        return $this->_inputType;
    }

    /**
     * Get the renderer to use for this value type
     * @return object
     */
    public function getValueElementRenderer()
    {
        return Mage::getBlockSingleton('rule/editable');
    }

    /**
     * Placed to fix backwards compatibility with magento < 1.6
     *
     * @return type
     */
    public function getValueSelectOptions()
    {
        $valueOption = $opt = array();
        if ($this->hasValueOption()) {
            $valueOption = (array) $this->getValueOption();
        }
        foreach ($valueOption as $k => $v) {
            $opt[] = array('value' => $k, 'label' => $v);
        }

        return $opt;
    }

}

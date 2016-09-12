<?php

/**
 *
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Limiter extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Abstract
{

    protected $_inputType = 'text';

    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_additional_conditions_limiter')
            ->setProcessingOrder(1);
    }

    public function loadOperatorOptions()
    {
        $this->setOperatorOption(
            array(
            'FIRST' => Mage::helper('rule')->__('the first'),
            'LAST' => Mage::helper('rule')->__('the last'),
            'OFFSET' => Mage::helper('rule')->__('offset (start[,length])')
            )
        );

        return $this;
    }

    /**
     * Render this as html
     * @return string
     */
    public function asHtml()
    {
        try {
            $html = $this->getTypeElement()->getHtml() .
                    Mage::helper('dyncatprod')->__("Limit the result to %s %s products", $this->getOperatorElement()->getHtml(), $this->getValueElement()->getHtml());
            if ($this->getId() != '1') {
                $html .= $this->getRemoveLinkHtml();
            }
        } catch (Exception $e) {
            $break = 1;

            return '';
        }

        return $html;
    }

    /**
     * validate
     *
     * @param  Varien_Object $object Quote
     * @return boolean
     */
    public function validate(Varien_Object $object)
    {
        $collection = $object->getCollection();
        $operator = $this->getOperator();
        $length = $this->getValueParsed();
        $start = 0;
        switch ($operator) {
        case 'LAST':
            $collection->getSelect()->order('e.entity_id ' . Zend_Db_Select::SQL_DESC);
            break;
        case 'OFFSET':
            $offset = explode(',', $length);
            $start = array_shift($offset);
            $length = array_shift($offset);
            break;
        }
        $collection->getSelect()->limit($length, $start);
        $this->getHelper()->debug('LIMITER SQL ADJUSTED: ' . $collection->getSelect());

        return true;
    }

}

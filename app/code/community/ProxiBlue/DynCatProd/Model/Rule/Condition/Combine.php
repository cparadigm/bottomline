<?php

/**
 * Conditions combine for dynamic products
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Combine extends ProxiBlue_DynCatProd_Model_Rule_Condition_Backport
{

    private $_startProcessingorder = 10;

    public function __construct()
    {
        parent::__construct();
        $this->setType('dynactprod/rule_condition_combine')
            ->setValue(true)
            ->setAggregator('all');
    }

    /**
     * Conditions child rules
     * Current supported:
     * Cart Subtotal
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions, array(
            array('value' => 'dyncatprod/rule_condition_product_found', 'label' => Mage::helper('dyncatprod')->__('Product Atribute Combination')),
            array('value' => 'dyncatprod/rule_condition_additional_conditions_limiter', 'label' => Mage::helper('dyncatprod')->__('Limit Results')),
            array('value' => 'dyncatprod/rule_condition_additional_conditions_transformations', 'label' => Mage::helper('dyncatprod')->__('Product Transformations')),
            array('value' => 'dyncatprod/rule_condition_category_control', 'label' => Mage::helper('dyncatprod')->__('Category Control Combination')),
            )
        );

        return $conditions;
    }

    /**
     * Render condions as html
     * @return string
     */
    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml();
        //Mage::helper('rule')->__('If %s of these conditions are %s:', $this->getAggregatorElement()->getHtml(), $this->getValueElement()->getHtml());
        if ($this->getId() != '1') {
            $html.= $this->getRemoveLinkHtml();
        }

        return $html;
    }

    /**
     * Build the htlm rule selections for conditons
     * @return string
     */
    public function asHtmlRecursive()
    {
        $html = $this->asHtml() . '<ul id="' . $this->getPrefix() . '__' . $this->getId() . '__children" class="rule-param-start">';
        foreach ($this->getConditions() as $cond) {
            $html .= '<li>' . $cond->asHtmlRecursive() . '</li>';
        }
        $html .= '<li>' . $this->getNewChildElement()->getHtml() . '</li></ul>';

        return $html;
    }

    /**
     * Build the condition array.
     *
     * From v3 this sets a processing order found form the class objects
     * This is set via a constant in the class named 'PROCESSING_ORDER';
     *
     * @param  type $condition
     * @return \ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Found
     */
    public function addCondition($condition)
    {
        $condition->setRule($this->getRule());
        $condition->setObject($this->getObject());
        $condition->setPrefix($this->getPrefix());

        $conditions = $this->getConditions();
        if ($condition->getProcessingOrder() == 'last') {
            $conditions[] = $condition;
        } else {
            if (array_key_exists($this->getProcessingOrder($condition), $conditions)) {
                mage::helper('dyncatprod')->debug('Rule ' . get_class($condition) . ' appear to have the same processing order than another rule. This can result in unexpected rules behaviour. Contact sales@proxiblue.com.au to correct this.');
                $condition->setProcessingOrder($this->getProcessingOrder($condition) + rand(200, 500) + rand(200, 500));
            }
            $conditions[$this->getProcessingOrder($condition)] = $condition;
        }
        if (!$condition->getId()) {
            $condition->setId($this->getId() . '--' . sizeof($conditions));
        }
        $this->setData($this->getPrefix(), $conditions);

        return $this;
    }

    protected function getProcessingOrder($condition)
    {
        if ($condition->getProcessingOrder() === false || is_null($condition->getProcessingOrder())) {
            $this->_startProcessingorder = $this->_startProcessingorder + 1;
            $condition->setProcessingOrder($this->_startProcessingorder);
        }

        return $condition->getProcessingOrder();
    }

    /**
     * Load the conditons into the required objects.
     *
     * From v3, this sorts the conritions as per set processing order
     *
     * @param  type $arr
     * @param  type $key
     * @return \ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Found
     */
    public function loadArray($arr, $key = 'conditions')
    {
        $this->setAggregator(isset($arr['aggregator']) ? $arr['aggregator'] : (isset($arr['attribute']) ? $arr['attribute'] : null))
            ->setValue(isset($arr['value']) ? $arr['value'] : (isset($arr['operator']) ? $arr['operator'] : null));
        if (!empty($arr[$key]) && is_array($arr[$key])) {
            foreach ($arr[$key] as $condArr) {
                try {
                    $cond = $this->_getNewConditionModelInstance($condArr['type']);
                    if ($cond) {
                        $this->addCondition($cond);
                        $cond->loadArray($condArr, $key);
                    }
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
        $conditions = $this->getConditions();
        ksort($conditions, SORT_NUMERIC);
        $this->setConditions($conditions);

        return $this;
    }

    /**
     * all rules must validate for results
     * @return string
     */
    public function getAggregator()
    {
        if (!$this->getData('aggregator')) {
            return 'all';
        } else {
            return $this->getData('aggregator');
        }
    }

    /**
     * result must be true
     * @return string
     */
    public function getValue()
    {
        if (!$this->getData('value')) {
            return true;
        } else {
            return $this->getData('value');
        }
    }

    public function validate(Varien_Object $object)
    {
        if (!$this->getConditions()) {
            return false;
        }

        $all = $this->getAggregator() === 'all';
        $true = (bool) $this->getValue();

        $object->setWhereCollector(array());

        foreach ($this->getConditions() as $cond) {
            $object->setWhereCollector(array_merge($object->getWhereCollector(), array($cond->getId() => array())));
            $validated = $cond->validate($object);

            if ($all && $validated !== $true) {
                return false;
            }
        }

        $select = $object->getCollection()->getSelect();
        $select->reset(Zend_Db_Select::WHERE);
        $whereCollector = array_reverse($object->getWhereCollector());
        // flatten the whereCollector condition parts
        $stripFirstLinker = false;
        foreach ($whereCollector as $key => $condWhereParts) {
            if (is_null($condWhereParts) || count($condWhereParts) == 0) {
                unset($whereCollector[$key]);
                continue;
            }
            // adjust the conditons where parts to OR if we are using ANY
            foreach ($this->getConditions() as $cond) {
                if ($cond->getId() == $key && $cond->getAggregator() == 'any') {
                    foreach ($condWhereParts as $partKey => $clause) {
                        if (strpos($clause, 'at_special_to_date') == false
                            && strpos($clause, 'at_special_from_date') == false
                            && strpos($clause, 'at_special_price') == false
                        ) {
                            $condWhereParts[$partKey] = str_replace('AND', 'OR', $clause);
                        }
                    }
                }
            }
            mage::helper('dyncatprod')->debug('SQL CONVERTED TO ALL: ' . $select);
            if ($stripFirstLinker) {
                reset($condWhereParts);
                $first = key($condWhereParts);
                $condWhereParts[$first] = trim(str_replace('AND', '', str_replace('OR', '', $condWhereParts[$first])));
            }
            $whereCollector[$key] = "(" . implode(" ", $condWhereParts) . ")";
            $stripFirstLinker = true;
        }
        // now combine them into one where clause
        $isFirstCondition = true;
        $combined = false;
        foreach ($this->getConditions() as $cond) {
            if (array_key_exists($cond->getId(), $whereCollector)) {
                if (!$isFirstCondition) {
                    $combiner = ($cond->getCombiner()) ? $cond->getCombiner() : 'AND';
                    $combined .= " " . $combiner . " " . $whereCollector[$cond->getId()];
                } else {
                    $combined = $whereCollector[$cond->getId()];
                    $isFirstCondition = false;
                }
            }
        }

        if ($combined !== false) {
            $select->setPart(Zend_Db_Select::WHERE, (array) $combined);
            mage::helper('dyncatprod')->debug('SQL COMBINED: ' . $select);
        }

        return $validated;
    }

}

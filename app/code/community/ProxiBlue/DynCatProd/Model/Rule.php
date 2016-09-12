<?php

/**
 *  Dynamic Products rule
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule extends Mage_CatalogRule_Model_Rule
{

    protected $_eventPrefix = 'dyncatprod_rule';
    protected $_eventObject = 'dyncatprodRule';

    /**
     * Internal holder for helper class
     *
     * @var object
     */
    private $_helper;

    /**
     * Set resource model and Id field name
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('dyncatprod/rule');
        $this->setIdFieldName('rule_id');
    }

    /**
     * Wrapper loadPost introduced in v3 to accomodate ability to
     * refactor rules to new format used in v3
     *
     * @param array  $data     Holds conditions in key called condtitions
     * @param object $category The active category
     *
     * @return void
     */
    public function preLoadPost(array $data, $category)
    {
        $conditions = array();
        if (array_key_exists('conditions', $data) && is_string($data['conditions'])) {
            try {
                $conditions = unserialize($data['conditions']);
            } catch (Exception $e) {
                mage::logException($e);
            }
        }
        $conditions = $this->_convertLegacyRule($conditions, $category);
        $this->loadPost($conditions);
    }

    /**
     * Initialize rule model data from array.
     * Set store labels if applicable.
     *
     * @param array $conditions Array holding the conditions
     *
     * @return Mage_SalesRule_Model_Rule
     */
    public function loadPost(array $conditions)
    {
        $arr = $this->_convertFlatToRecursive(array('conditions' => $conditions));
        if (isset($arr['conditions'])) {
            if (array_key_exists("1", $arr['conditions'])) {
                $this->getConditions()->setConditions(array())->loadArray($arr['conditions'][1]);
            }
        }

        return $this;
    }

    /**
     * Get rule condition combine model instance
     *
     * @return object ProxiBlue_DynCatProd_Model_RUle_Condition_Combine
     */
    public function getConditionsInstance()
    {
        $conditionsModel = Mage::getModel('dyncatprod/rule_condition_combine');
        return $conditionsModel;
    }

    /**
     * Negates actions for this object
     *
     * @return string
     */
    public function getActionsInstance()
    {
        return 'No Actions'; // no actions in this rule
    }

    /**
     * we don't really save, we just want the object with the data ready to save
     * which is then stored in the catgeory attribute dyncatprod_attributes
     *
     * @return \ProxiBlue_DynCatProd_Model_Rule
     */
    public function save()
    {
        return $this;
    }

    /**
     * Convert Pre v3 rules to v3 rules layout
     *
     * @param  array  $value    The value of the rule
     * @param  object $category The active category
     *
     * @return array
     */
    private function _convertLegacyRule($value, $category)
    {
        $arr = array();
        if (is_array($value)) {
            foreach ($value as $ruleKey => $ruleData) {
                $path = explode('--', $ruleKey);
                if (count($path) == 1) {
                    // this is a rule container, so what is it?
                    if (array_key_exists('type', $ruleData)) {
                        switch ($ruleData['type']) {
                            case "dyncatprod/rule_condition_product_found":
                                //ok, this should not be rule container 1, it is a legacy rule.
                                // convert it by injecting the combine comtainer as 1, and shift all
                                // rules by 1.
                                $arr['1'] = array('new_child' => '',
                                    'value' => true,
                                    'aggregator' => 'all',
                                    'type' => 'dyncatprod/rule_condition_combine'
                                );
                                // shift all rules
                                $splitout = 2;
                                foreach ($value as $shiftRuleKey => $shiftRuleData) {
                                    $shiftPath = explode('--', $shiftRuleKey);
                                    // split out legacy rules to new layers
                                    switch ($shiftRuleData['type']) {
                                        case "dyncatprod/rule_condition_additional_conditions_limiter":
                                            $arr['1--' . $splitout] = $shiftRuleData;
                                            $splitout++;
                                            break;
                                        case "dyncatprod/rule_condition_additional_conditions_transformations":
                                            $arr['1--' . $splitout] = array('new_child' => '',
                                            'value' => true,
                                            'aggregator' => 'all',
                                            'type' => 'dyncatprod/rule_condition_additional_conditions_transformations'
                                            );
                                            $arr['1--' . $splitout . '--1'] = array(
                                            'value' => $shiftRuleData['value'],
                                            'operator' => $shiftRuleData['operator'],
                                            'type' => 'dyncatprod/rule_condition_additional_conditions_transformations_parents'
                                            );
                                        default:
                                            if ($shiftRuleData['type'] !=
                                                "dyncatprod/rule_condition_additional_conditions_transformations") {
                                                    $arr['1--' . $shiftRuleKey] = $shiftRuleData;
                                            }
                                            break;
                                    }
                                }
                                break;
                        }
                    }
                }
            }
        }
        if (count($arr) > 0) {
            $category->setDynamicAttributes(serialize($arr));
            try {
                $category->save();
            } catch (Exception $ex) {
                mage::logException($ex);
            }
            mage::log('DYNCATPROD: Category ' . $category->getId() . ' rules were updated to new format');
            return $arr;
        }
        return $value;
    }

}

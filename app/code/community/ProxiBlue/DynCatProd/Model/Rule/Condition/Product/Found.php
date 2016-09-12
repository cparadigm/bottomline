<?php

/**
 * Product promo rule
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Found extends ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Combine
{

    protected $_orderCount = 0;

    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_product_found')
            ->setAggregator('all')
            ->setValue(true)
            ->setConditions(array())
            ->setActions(array())
            ->setCombiner('AND');
        $this->loadCombinerOptions();
        if ($options = $this->getCombinerOptions()) {
            foreach ($options as $combiner => $dummy) {
                $this->setCombiner($combiner);
                break;
            }
        }
    }

    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml();
        if ($this->getProcessingOrder() == 11) {
            $html .= Mage::helper('dyncatprod')->__("If a product is in the catalog with %s of these conditions %s:", $this->getAggregatorElement()->getHtml(), $this->getValueElement()->getHtml());
        } else {
            $html .= Mage::helper('dyncatprod')->__("%s If a product is in the catalog with %s of these conditions %s:", $this->getCombinerElement()->getHtml(), $this->getAggregatorElement()->getHtml(), $this->getValueElement()->getHtml());
        }
        if ($this->getId() != '1') {
            $html.= $this->getRemoveLinkHtml();
        }

        return $html;
    }

    /**
     * Make sure value is set to TRUE if null.
     * Placed for backwards compatibility to pre v3.
     *
     * @return boolean
     */
    public function getValue()
    {
        $value = parent::getValue();
        if (is_null($value)) {
            $this->setValue(true);
        }

        return $this->getData('value');
    }

    public function validate(Varien_Object $object)
    {
        if (!$this->getConditions()) {
            return false;
        }

        $all = $this->getAggregator() === 'all';
        $true = (bool) $this->getValue();

        foreach ($this->getConditions() as $cond) {
            $validated = $cond->validate($object);

            if ($all && $validated !== $true) {
                return false;
            }
        }
        // group the WHERE for this subset into one group
        $select = $object->getCollection()->getSelect();
        $wherePart = $select->getPart(Zend_Db_Select::WHERE);
        $whereCollector = $object->getWhereCollector();
        $whereCollector[$this->getId()] = $wherePart;
        $object->setWhereCollector($whereCollector);
        // reset the collection where part so we can start fresh
        $select->reset(Zend_Db_Select::WHERE);

        return $validated;
    }

    public function loadCombinerOptions()
    {
        $this->setCombinerOption(
            array(
            'AND' => Mage::helper('rule')->__('AND'),
            'OR' => Mage::helper('rule')->__('OR'),
            )
        );

        return $this;
    }

    public function getCombinerSelectOptions()
    {
        $opt = array();
        foreach ($this->getCombinerOption() as $k => $v) {
            $opt[] = array('value' => $k, 'label' => $v);
        }

        return $opt;
    }

    public function getCombinerName()
    {
        return $this->getCombinerOption($this->getCombiner());
    }

    public function getCombinerElement()
    {
        if (is_null($this->getCombiner())) {
            foreach ($this->getCombinerOption() as $k => $v) {
                $this->setCombiner($k);
                break;
            }
        }

        return $this->getForm()->addField(
            $this->getPrefix() . '__' . $this->getId() . '__combiner', 'select', array(
                    'name' => 'rule[' . $this->getPrefix() . '][' . $this->getId() . '][combiner]',
                    'values' => $this->getCombinerSelectOptions(),
                    'value' => $this->getCombiner(),
                    'value_name' => $this->getCombinerName(),
                )
        )->setRenderer(Mage::getBlockSingleton('rule/editable'));
    }

    /**
     * Make sure combiner value is set to AND if null.
     * Placed for backwards compatibility to pre v3.
     *
     * @return boolean
     */
    public function getCombiner()
    {
        if (is_null($this->getData('combiner'))) {
            $this->setCombiner('AND');
        }

        return strtoupper($this->getData('combiner'));
    }

}

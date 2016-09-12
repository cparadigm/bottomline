<?php

/**
 * Conditions combine for additional rules that require all and any forced
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Combine extends Mage_Rule_Model_Condition_Combine
{

    public function __construct()
    {
        parent::__construct();
        $this->setValue(true)
            ->setAggregator('any');
    }

    /*
     * Force to true
     */

    public function getValue()
    {
        return true;
    }

    /*
     * Force to all. Must be all to validate all rules given.
     */

    public function getAggregator()
    {
        return 'all';
    }

    /**
     * Trap potential field duplication.
     * This should not happen, but can is someone ads two of the same identical rules (like two limiters)
     *
     * @return type
     */
    public function getTypeElement()
    {
        try {
            return $this->getForm()->addField(
                $this->getPrefix() . '__' . $this->getId() . '__type', 'hidden', array(
                        'name' => 'rule[' . $this->getPrefix() . '][' . $this->getId() . '][type]',
                        'value' => $this->getType(),
                        'no_span' => true,
                        'class' => 'hidden',
                )
            );
        } catch (Exception $e) {
            return $this->getForm()->addField(
                $this->getPrefix() . '__' . $this->getId() . '__' . rand(1, 100) . '__type', 'hidden', array(
                        'name' => 'rule[' . $this->getPrefix() . '][' . $this->getId() . '][type]',
                        'value' => $this->getType(),
                        'no_span' => true,
                        'class' => 'hidden',
                )
            );
        }
    }

}

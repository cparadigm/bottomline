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
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Transformations_Parents
extends ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Abstract
{

    protected $_inputType = 'select';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType('dyncatprod/rule_condition_additional_conditions_transformations_parents')
            ->setValue(null);
    }

    /**
     * Populate the internal Operator data with accepatble operators
     *
     * @return object ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Transformations_Parents
     */
    public function loadOperatorOptions()
    {
        $this->setOperatorOption(
            array(
            'RS' => Mage::helper('rule')->__('then replace it with '),
            '+C' => Mage::helper('rule')->__('then add the simple and also add '),
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
        $html = $this->getTypeElement()->getHtml() .
                Mage::helper('dyncatprod')->__(
                    "If a simple product was found %s %s",
                    $this->getOperatorElement()->getHtml(),
                    $this->getValueElement()->getHtml()
                );
        if ($this->getId() != '1') {
            $html .= $this->getRemoveLinkHtml();
        }

        return $html;
    }


    /**
     * Validate
     *
     * Simply place a flag to run this rules vlidateLater method after collection was built.
     *
     * @param type $object
     *
     * @return boolean
     */
    public function validate(Varien_Object $object) {
        $collection = $object->getCollection();
        $collection->setFlag('transform_parents',$this);
        return true;
    }

    /**
     * validateLater
     * This is a special case rule.
     *
     * To get the parents of simples the following actions must take place:
     *
     * 1. load the given product collection
     * 2. Iterate the results, and determine if that product has a parent.
     * 3. If it does have a parent:
     * 3.1 If replace: add it, remove the simple
     * 3.2 If add simply add it to the collection result
     * 4. set a 'replace ids' flag into the collection to allow the originator code to use this set of ids,
     *    and not the collection
     *
     * @param  Varien_Object $object Quote
     * @return boolean
     */
    public function validateLater(Varien_Object $object)
    {
        $collection = $object->getCollection();
        mage::Helper('dyncatprod')->debug(
            "Product collection before transfomation of parents: "
            . $collection->getSelect()
        );
        $productsToReturn = array();
        foreach ($collection as $key => $product) {
            $this->getParentData($product, $productsToReturn, $key);
        }
        // some products contain comma seperated items, as they belong to multiple parents
        // this will make one entry per array
        $productsToReturn = implode(',', $productsToReturn);
        $productsToReturn = explode(',', $productsToReturn);
        // eliminate duplicates
        $productsToReturn = array_unique($productsToReturn);
        $collection->setFlag('replace_ids', $productsToReturn);

        return true;
    }

    /**
     * Get the parent data of the given product object
     * @param  object  $product
     * @param  array   $productsToReturn
     * @param  integer $orderKey
     *
     * @return array
     */
    private function getParentData($product, &$productsToReturn, $orderKey = 0)
    {
        try {
            if (is_object($product)) {
                if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {
                    $parentIds = Mage::getModel('catalog/product_type_configurable')
                        ->getParentIdsByChild($product->getId()); //check for config product
                    if (!$parentIds) {
                        $parentIds = Mage::getModel('catalog/product_type_grouped')
                            ->getParentIdsByChild($product->getId()); // check for grouped product
                    }
                    if ($parentIds) {
                        mage::helper('dyncatprod')->debug(
                            'Parent '
                            . implode(',', $parentIds)
                            . ' for simple '
                            . $product->getId()
                        );
                        $productsToReturn[$orderKey] = implode(',', $parentIds);
                        if ($this->getOperator() != 'RS') {
                            mage::helper('dyncatprod')->debug(
                                'simple '
                                . $product->getId()
                                . ' was placed back after parent'
                            );
                            $productsToReturn[$orderKey] = $productsToReturn[$orderKey] . ',' . $product->getId();
                        }
                    } else {
                        $productsToReturn[$orderKey] = $product->getId();
                    }
                } else {
                    $productsToReturn[$orderKey] = $product->getId();
                }
            }

            return $productsToReturn;
        } catch (Exception $e) {
            $break = 1;
        }
    }

    /**
     * Populate the available Value options for the rule in admin
     *
     * @return \ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Transformations_Parents
     */
    public function loadValueOptions()
    {
        $this->setValueOption(
            array(
            'parent' => Mage::helper('rule')->__('Configurable or Group Parent Product')
            )
        );

        return $this;
    }

}

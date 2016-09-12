<?php

/**
 * Catalog Rule Conditions
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Category_Conditions_Category extends ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Abstract
{

    /**
     * validate
     *
     * @param  Varien_Object $object Quote
     * @return boolean
     */
    public function validate(Varien_Object $object)
    {
        // do the action;
        $currentValue = $object->getData($this->getAttribute());
        if ($currentValue != $this->getValue()) {
            $object->setData($this->getAttribute(), $this->getValue());
            // do we need to send a notification?
            if ($object->getIsDynamicCronRun() && mage::getStoreConfig('dyncatprod/notify/category_disabled') && $this->getAttribute() == 'is_active' && $this->getValue() == 0) {
                $message = Mage::helper('core')->__("Category '%s' (%s) was disabled.", $object->getName(), $object->getId());
                mage::helper('dyncatprod')->sendEmail(Mage::helper('core')->__("Dynamic Category Products Category Control Notification for %s", $object->getName()), $message);
            }
        }

        return true;
    }

    public function asHtml()
    {
        $html = Mage::helper('dyncatprod')->__("Set the attribute <strong>%s</strong> to have the value of&nbsp; %s ", $this->getAttributeElementHtml(), $this->getValueElementHtml());
        $html = $this->getTypeElementHtml()
                . $html
                . $this->getRemoveLinkHtml()
        ;

        return $html;
    }

    /**
     * Load attribute options
     *
     * @return Mage_CatalogRule_Model_Rule_Condition_Product
     */
    public function loadAttributeOptions()
    {
        $categoryAttributes = Mage::getResourceSingleton('catalog/category')
                ->loadAllAttributes()
                ->getAttributesByCode();

        $attributes = array();

        foreach ($categoryAttributes as $attribute) {
            if ($attribute->getFrontendLabel() == 'dynamic_attributes') {
                continue;
            }
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            if (!$attribute->getFrontendLabel() || $attribute->getBackendType() == 'datetime' || $attribute->getBackendType() == 'date') {
                continue;
            }

            $attributes[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
        }

        asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }

    public function getValueElementRenderer()
    {
        if (strpos($this->getValueElementType(), '/') !== false) {
            return Mage::getBlockSingleton($this->getValueElementType());
        }

        return Mage::getBlockSingleton('dyncatprod/rule_editable');
    }

    /**
     * Retrieve attribute object
     *
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
    public function getAttributeObject()
    {
        try {
            $obj = Mage::getSingleton('eav/config')
                    ->getAttribute(Mage_Catalog_Model_Category::ENTITY, $this->getAttribute());
        } catch (Exception $e) {
            $obj = new Varien_Object();
            $obj->setEntity(Mage::getResourceSingleton('catalog/category'))
                ->setFrontendInput('text');
        }

        return $obj;
    }

    /**
     * Retrieve value element type
     *
     * @return string
     */
    public function getValueElementType()
    {
        if ($this->getAttribute() === 'is_in_stock' || $this->getAttribute() === 'type_id' || $this->getAttribute() === 'attribute_set_id') {
            return 'multiselect';
        }
        if ($this->getAttribute() === 'created_at' || $this->getAttribute() === 'updated_at') {
            return 'date';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'text';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
        case 'select':
        case 'boolean':
            return 'select';
        case 'multiselect':
            return 'multiselect';
        case 'date':
            return 'date';
        default:
            return 'text';
        }
    }

}

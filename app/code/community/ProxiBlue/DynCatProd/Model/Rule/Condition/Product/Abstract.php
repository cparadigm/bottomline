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
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Product_Abstract extends ProxiBlue_DynCatProd_Model_Rule_Condition_Abstract
{

    /**
     * All attribute values as array in form:
     * array(
     *   [entity_id_1] => array(
     *          [store_id_1] => store_value_1,
     *          [store_id_2] => store_value_2,
     *          ...
     *          [store_id_n] => store_value_n
     *   ),
     *   ...
     * )
     *
     * Will be set only for not global scope attribute
     *
     * @var array
     */
    protected $_entityAttributeValues = null;
    protected $_transformAttributes = array();
    protected $_dropAtrribute = array();
    protected $_arrayInputTypes = array();

    public function __construct()
    {
        // map date attributes to be 'within range'
        // does the map xml exist
        $xmlFile = Mage::getModuleDir('etc', 'ProxiBlue_DynCatProd') . DS . 'date_range_attributes.xml';
        if (file_exists($xmlFile)) {
            try {
                $dateRangeXml = simplexml_load_string(file_get_contents($xmlFile));
                foreach ($dateRangeXml->transform as $key => $transforms) {
                    $this->_transformAttributes = array((string) $transforms->code->end => (string) $transforms->description);
                    //$this->_dropAtrribute = array((string) $transforms->code->end => (string) $transforms->code->start);
                }
            } catch (Exception $e) {
                mage::logException($e);
            }
        }
        parent::__construct();
    }

    /**
     * Customize default operator input by type mapper for some types
     *
     * @return array
     */
    public function getDefaultOperatorInputByType()
    {
        if (null === $this->_defaultOperatorInputByType) {
            parent::getDefaultOperatorInputByType();
            /*
             * '{}' and '!{}' are left for back-compatibility and equal to '==' and '!='
             */
            $this->_defaultOperatorInputByType['category'] = array('==', '!=', '()', '!()');
            $this->_arrayInputTypes[] = 'category';
            $this->_defaultOperatorInputByType['category_child'] = array('==', '!=', '()', '!()');
            $this->_arrayInputTypes[] = 'category_child';
            $this->_defaultOperatorInputByType['applied_catalog_rule_id'] = array('==', '()');
            $this->_arrayInputTypes[] = 'applied_catalog_rule_id';
        }

        return $this->_defaultOperatorInputByType;
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
                    ->getAttribute(Mage_Catalog_Model_Product::ENTITY, $this->getAttribute());
        } catch (Exception $e) {
            $obj = new Varien_Object();
            $obj->setEntity(Mage::getResourceSingleton('catalog/product'))
                ->setFrontendInput('text');
        }

        return $obj;
    }

    /**
     * Add special attributes
     *
     * @param array $attributes
     */
    protected function _addSpecialAttributes(array &$attributes)
    {
        $attributes['special']['attribute_set_id'] = Mage::helper('catalogrule')->__('Attribute Set');
        $attributes['special']['category_ids'] = Mage::helper('catalogrule')->__('Category');
        $attributes['special']['category_child'] = Mage::helper('catalogrule')->__('Category Parent');
        $attributes['special']['applied_catalog_rule_id'] = Mage::helper('catalogrule')->__('Applied Catalog Rule');
        $attributes['special']['is_in_stock'] = Mage::helper('catalogrule')->__('Stock Availability');
        $attributes['special']['created_at'] = Mage::helper('catalogrule')->__('Created At Date');
        $attributes['special']['updated_at'] = Mage::helper('catalogrule')->__('Updated At Date');
        $attributes['special']['type_id'] = Mage::helper('catalogrule')->__('Product Type');
    }

    /**
     * Load attribute options
     *
     * @return Mage_CatalogRule_Model_Rule_Condition_Product
     */
    public function loadAttributeOptions()
    {
        $productAttributes = Mage::getResourceSingleton('catalog/product')
                ->loadAllAttributes()
                ->getAttributesByCode();

        $attributes = array('normal' => array(), 'date_range' => array(), 'special' => array());

        foreach ($productAttributes as $attribute) {
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            if (!$attribute->getFrontendLabel()) {
                continue;
            }
            if ($attribute->getBackendType() == 'datetime' || $attribute->getBackendType() == 'date') {
                $label = ($attribute->getFrontendLabel() != '') ? $attribute->getFrontendLabel() : $attribute->getAttributeCode();
                if (strpos($attribute->getAttributeCode(), '_from') !== false || in_array($attribute->getAttributeCode(), $this->_dropAtrribute)) {
                    // keep the attribute to stil appear in normal section
                    $attributes['normal'][$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
                    continue;
                }
                if (strpos($attribute->getAttributeCode(), '_to') !== false) {
                    $label = str_replace('To', '', $label);
                    $label = str_replace('to', '', $label);
                    $label .= ' From/To';
                }
                if (array_key_exists($attribute->getAttributeCode(), $this->_transformAttributes)) {
                    $label = $this->_transformAttributes[$attribute->getAttributeCode()];
                }

                $attributes['date_range'][$attribute->getAttributeCode()] = $label;
                // keep the adjusted date range attributes to also still appear in the attribute list
                $attributes['normal'][$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
                // is the attribute listed in the date range xml
            } else {
                $attributes['normal'][$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
            }
        }

        $this->_addSpecialAttributes($attributes);

        asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * Prepares values options to be used as select options or hashed array
     * Result is stored in following keys:
     *  'value_select_options' - normal select array: array(array('value' => $value, 'label' => $label), ...)
     *  'value_option' - hashed array: array($value => $label, ...),
     *
     * @return Mage_CatalogRule_Model_Rule_Condition_Product
     */
    protected function _prepareValueOptions()
    {
        // Check that both keys exist. Maybe somehow only one was set not in this routine, but externally.
        $selectReady = $this->getData('value_select_options');
        $hashedReady = $this->getData('value_option');
        if ($selectReady && $hashedReady) {
            return $this;
        }

        // Get array of select options. It will be used as source for hashed options
        $selectOptions = null;
        if ($this->getAttribute() === 'attribute_set_id') {
            $entityTypeId = Mage::getSingleton('eav/config')
                            ->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getId();
            $selectOptions = Mage::getResourceModel('eav/entity_attribute_set_collection')
                    ->setEntityTypeFilter($entityTypeId)
                    ->load()
                    ->toOptionArray();
        } elseif ($this->getAttribute() === 'is_in_stock') {
            $selectOptions = Mage::getModel('CatalogInventory/source_stock')
                    ->toOptionArray();
        } elseif ($this->getAttribute() === 'type_id') {
            $options = Mage::getSingleton('catalog/product_type')->getOptionArray();
            $selectOptions = array();
            foreach ($options as $optionKey => $option) {
                $selectOptions[] = array('label' => $option, 'value' => $optionKey);
            }
        } elseif (is_object($this->getAttributeObject())) {
            $attributeObject = $this->getAttributeObject();
            if ($attributeObject->usesSource()) {
                if ($attributeObject->getFrontendInput() == 'multiselect') {
                    $addEmptyOption = false;
                } else {
                    $addEmptyOption = true;
                }
                $selectOptions = $attributeObject->getSource()->getAllOptions($addEmptyOption);
            }
        }

        // Set new values only if we really got them
        if ($selectOptions !== null) {
            // Overwrite only not already existing values
            if (!$selectReady) {
                $this->setData('value_select_options', $selectOptions);
            }
            if (!$hashedReady) {
                $hashedOptions = array();
                foreach ($selectOptions as $o) {
                    if (is_array($o['value'])) {
                        continue; // We cannot use array as index
                    }
                    $hashedOptions[$o['value']] = $o['label'];
                }
                $this->setData('value_option', $hashedOptions);
            }
        }

        return $this;
    }

    /**
     * Retrieve value by option
     *
     * @param  mixed $option
     * @return string
     */
    public function getValueOption($option = null)
    {
        $this->_prepareValueOptions();

        return $this->getData('value_option' . (!is_null($option) ? '/' . $option : ''));
    }

    /**
     * Retrieve select option values
     *
     * @return array
     */
    public function getValueSelectOptions()
    {
        $this->_prepareValueOptions();

        return $this->getData('value_select_options');
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
        case 'applied_catalog_rule_id':
            $image = Mage::getDesign()->getSkinUrl('images/rule_chooser_trigger.gif');
            break;
        }

        if (!empty($image)) {
            $html = '<a href="javascript:void(0)" class="rule-chooser-trigger"><img src="' . $image . '" alt="" class="v-middle rule-chooser-trigger" title="' . Mage::helper('rule')->__('Open Chooser') . '" /></a>';
        }

        return $html;
    }

    /**
     * Retrieve attribute element
     *
     * @return Varien_Form_Element_Abstract
     */
    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);

        return $element;
    }

    /**
     * Collect validated attributes
     *
     * @param  Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $productCollection
     * @return Mage_CatalogRule_Model_Rule_Condition_Product
     */
    public function collectValidatedAttributes($productCollection)
    {
        $attribute = $this->getAttribute();
        if ('category_ids' != $attribute || 'category_child' != $attribute || 'applied_catalog_rule_id' != $attribute) {
            if ($this->getAttributeObject()->isScopeGlobal()) {
                $attributes = $this->getRule()->getCollectedAttributes();
                $attributes[$attribute] = true;
                $this->getRule()->setCollectedAttributes($attributes);
                $productCollection->addAttributeToSelect($attribute, 'left');
            } else {
                $this->_entityAttributeValues = $productCollection->getAllAttributeValues($attribute);
            }
        }

        return $this;
    }

    /**
     * Retrieve input type
     *
     * @return string
     */
    public function getInputType()
    {
        if ($this->getAttribute() === 'attribute_set_id' || $this->getAttribute() === 'type_id' || $this->getAttribute() === 'is_in_stock') {
            return 'multiselect';
        }
        if ($this->getAttribute() === 'created_at' || $this->getAttribute() === 'updated_at') {
            return 'date';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'string';
        }
        if ($this->getAttributeObject()->getAttributeCode() == 'category_child' || $this->getAttributeObject()->getAttributeCode() == 'category_ids') {
            return 'category';
        }

        if ($this->getAttributeObject()->getAttributeCode() == 'applied_catalog_rule_id') {
            return 'applied_catalog_rule_id';
        }
        if (strpos($this->getAttributeObject()->getAttributeCode(), '_from') || strpos($this->getAttributeObject()->getAttributeCode(), '_to') || in_array($this->getAttributeObject()->getAttributeCode(), $this->_dropAtrribute) || array_key_exists($this->getAttributeObject()->getAttributeCode(), $this->_transformAttributes)
        ) {
            return 'date_range';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
        case 'select':
        case 'multiselect':
            return 'multiselect';

        case 'date':
            return 'date';

        case 'boolean':
            return 'boolean';

        default:
            return 'string';
        }
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
        case 'boolean':
            return 'select';
        case 'select':
        case 'multiselect':
            return 'multiselect';

        case 'date':
            return 'date';

        default:
            return 'text';
        }
    }

    /**
     * Retrieve value element
     *
     * @return Varien_Data_Form_Element_Abstract
     */
    public function getValueElement()
    {
        $elementParams = array(
            'name' => 'rule[' . $this->getPrefix() . '][' . $this->getId() . '][value]',
            'value' => $this->getValue(),
            'values' => $this->getValueSelectOptions(),
            'value_name' => $this->getValueName(),
            'after_element_html' => $this->getValueAfterElementHtml(),
            'explicit_apply' => $this->getExplicitApply(),
        );
        $inputType = $this->getInputType();
        if ($inputType == 'date' || $inputType == 'date_range') {
            // date format intentionally hard-coded
            $elementParams['input_format'] = Varien_Date::DATE_INTERNAL_FORMAT;
            $elementParams['format'] = Varien_Date::DATE_INTERNAL_FORMAT;
        }
        // add our custom date renderer to handle both date and text display
        $this->getForm()->addType('date_text', 'ProxiBlue_DynCatProd_Model_Data_Form_Element_Datetext');
        if (is_numeric($this->getData('value')) && $inputType == 'date') {
            $element = $this->getForm()->addField(
                $this->getPrefix() . '__' . $this->getId() . '__value', 'date_text', $elementParams
            )->setRenderer($this->getValueElementRenderer());
        } else {
            $element = $this->getForm()->addField(
                $this->getPrefix() . '__' . $this->getId() . '__value', $this->getValueElementType(), $elementParams
            )->setRenderer($this->getValueElementRenderer());
        }
        if (is_object($this->getAttributeObject())) {
            switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'date':
                $element->setImage(Mage::getDesign()->getSkinUrl('images/grid-cal.gif'));
                if (strpos($this->getAttributeObject()->getAttributeCode(), '_from') || strpos($this->getAttributeObject()->getAttributeCode(), '_to') || in_array($this->getAttributeObject()->getAttributeCode(), $this->_dropAtrribute) || array_key_exists($this->getAttributeObject()->getAttributeCode(), $this->_transformAttributes)) {
                    $element->setStyleInject('display:none;');
                }
                break;
            }
        }

        return $element;
    }

    /**
     * Retrieve value element chooser URL
     *
     * @return string
     */
    public function getValueElementChooserUrl()
    {
        $url = false;
        switch ($this->getAttribute()) {
        case 'sku':
        case 'category_ids':
        case 'category_child':
        case 'applied_catalog_rule_id':
            $url = 'adminhtml/promo_widget/chooser'
                    . '/attribute/' . $this->getAttribute();
            if ($this->getJsFormObject()) {
                $url .= '/form/' . $this->getJsFormObject();
            }
            break;
        }

        return $url !== false ? Mage::helper('adminhtml')->getUrl($url) : '';
    }

    /**
     * Retrieve Explicit Apply
     *
     * @return bool
     */
    public function getExplicitApply()
    {
        switch ($this->getAttribute()) {
        case 'sku':
        case 'category_ids':
        case 'category_child':
        case 'applied_catalog_rule_id':
            return true;
        }
        if (is_object($this->getAttributeObject())) {
            switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'date':
                return true;
            }
        }

        return false;
    }

    /**
     * Load array
     *
     * @param  array $arr
     * @return Mage_CatalogRule_Model_Rule_Condition_Product
     */
    public function loadArray($arr)
    {
        $this->setAttribute(isset($arr['attribute']) ? $arr['attribute'] : false);
        $attribute = $this->getAttributeObject();

        $isContainsOperator = !empty($arr['operator']) && in_array($arr['operator'], array('{}', '!{}'));
        if ($attribute && $attribute->getBackendType() == 'decimal' && !$isContainsOperator) {
            if (isset($arr['value'])) {
                if (!empty($arr['operator']) && in_array($arr['operator'], array('!()', '()')) && false !== strpos($arr['value'], ',')) {

                    $tmp = array();
                    foreach (explode(',', $arr['value']) as $value) {
                        $tmp[] = Mage::app()->getLocale()->getNumber($value);
                    }
                    $arr['value'] = implode(',', $tmp);
                } else {
                    $arr['value'] = Mage::app()->getLocale()->getNumber($arr['value']);
                }
            } else {
                $arr['value'] = false;
            }
            $arr['is_value_parsed'] = isset($arr['is_value_parsed']) ? Mage::app()->getLocale()->getNumber($arr['is_value_parsed']) : false;
        }
        parent::loadArray($arr);
        $this->setIsValueParsed(true);

        return $this;
    }

    /**
     * Validate product attrbute value for condition
     *
     * @param  Varien_Object $object
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
        $attrCode = $this->getAttribute();
        $collection = $object->getCollection();
        switch ($attrCode) {
        case 'category_ids':
            $value = $this->getValue();
            $operator = $this->_operatorMap[$this->getOperator()];
            if ($operator == 'finset' || $operator == 'eq') {
                $operator = 'in';
                $value = explode(',', $this->getValue());
                try {
                    $collection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id=entity_id', null, 'left');
                } catch (Exception $e) {
                    // fail silently. It simply means we already have this joined field defned.
                    //mage::log($e->getMessage());
                }
                    $collection->addAttributeToFilter('category_id', array($operator => $value));
            } elseif ($operator == 'nfinset' || $operator == 'neq') {
                $operator = 'nin';
                $value = explode(',', $this->getValue());
                // since a product can appear in multiple categories, we must eliminate via the product ids of any
                // products that has the given category id in it.
                $subCollection = mage::getModel('catalog/product')->getCollection();
                try {
                    $subCollection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id=entity_id', null, 'left');
                } catch (Exception $e) {
                    // fail silently. It simply means we already have this joined field defned.
                    //mage::log($e->getMessage());
                }
                    $subCollection->addAttributeToFilter('category_id', array('in' => $value));
                    // remove all columns, and nly get the entity_id
                    $subSelect = $subCollection->getSelect();
                    $columns = $subSelect->getPart(Zend_Db_Select::COLUMNS);
                    $subSelect->reset(Zend_Db_Select::COLUMNS);
                    $newColumns = array('0' => array('e', 'entity_id', null));
                    $subSelect->setPart(Zend_Db_Select::COLUMNS, $newColumns);
                    $this->getHelper()->debug("Category ID subquery:" . $subSelect);
                    $collection->getSelect()->where('e.entity_id NOT IN (?)', new Zend_Db_Expr($subSelect->__toString()));
            }
            break;
        case 'category_child':
            $value = explode(',', $this->getValue());
            $operator = $this->_operatorMap[$this->getOperator()];
            if ($operator == 'finset' || $operator == 'eq') {
                $operator = 'like';
            }
            if ($operator == 'nfinset' || $operator == 'neq') {
                $operator = 'nlike';
            }
            try {
                $collection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id=entity_id', null, 'left');
            } catch (Exception $e) {
                // fail silently. It simply means we already have this joined field defned.
                //mage::log($e->getMessage());
            }
            foreach ($value as $id) {
                $collection->joinField('path' . trim($id), 'catalog/category', 'path', 'entity_id=category_id', "FIND_IN_SET('" . trim($id) . "',REPLACE(at_path" . trim($id) . ".path,'/',','))>0", 'right');
            }
            $collection->setFlag('applied_catalog_rule_id', true);
            break;
        case 'applied_catalog_rule_id':
            $storeDate = Mage::app()->getLocale()->storeTimeStamp($this->getStoreId());
            $value = $this->getValue();
            $conditions = 'price_rule.product_id = e.entity_id AND ';
            $conditions .= "(from_time = 0
            OR from_time <= " . $storeDate . ")
            AND (to_time = 0
            OR to_time >= " . $storeDate . ") AND ";
            $conditions .= "price_rule.rule_id IN (" . $value . ")";
            $collection->getSelect()->joinInner(
                array('price_rule' => $collection->getTable('catalogrule/rule_product')), $conditions
            );
            $collection->setFlag('applied_catalog_rule_id', true);
            break;
        case 'is_in_stock':
            $value = $this->getValue();
            $operator = $this->_operatorMap[$this->getOperator()];
            try {
                $collection->joinField(
                    'is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', null, 'left'
                );
            } catch (Exception $e) {
                // fail silently. It simply means we already have this joined field defned.
                //mage::log($e->getMessage());
            }
                $filter = array();
            foreach ($value as $val) {
                $filter[] = array('attribute' => $attrCode, $operator => $val);
            }
                $collection->addAttributeToFilter('is_in_stock', $filter);
                $collection->setFlag('applied_catalog_rule_id', true);
            break;
        default:
            if (!isset($this->_entityAttributeValues[$object->getId()])) {
                $operator = $this->_operatorMap[$this->getOperator()];
                $value = $this->getValue();
                $eav_config = Mage::getModel('eav/config');
                $attribute = $eav_config->getAttribute('catalog_product', $attrCode);
                $mapToDates = array('created_at', 'updated_at');
                if (in_array($attrCode, $mapToDates)) {
                    $attribute->setFrontendInput('date');
                }
                switch ($attribute->getFrontendInput()) {
                case 'multiselect':
                    if ($operator == 'in') {
                        $operator = 'finset';
                    } elseif ($operator == 'nin') {
                        $operator = 'nfinset';
                    }
                        $filter = array();
                    foreach ($value as $val) {
                        $filter[] = array('attribute' => $attrCode, $operator => $val);
                    }
                        $collection->addAttributeToFilter($filter);
                    break;
                case 'select':
                    $value = array($operator => $value);
                    $collection->addAttributeToFilter($attribute, $value);
                    break;
                case 'date':
                    $todayDate = Mage::app()->getLocale()->date()->toString(Varien_Date::DATE_INTERNAL_FORMAT);
                    switch ($operator) {
                    case 'inrange':
                        //TO
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCode,
                            'null' => true
                            ),
                            array(
                            'attribute' => $attrCode,
                            'from' => $todayDate,
                            //'to'      => $todayDate,
                            'date' => true
                            )
                            )
                        );
                        // FROM
                        $attrCodeFrom = str_replace('to', 'from', $attrCode);
                        if (array_key_exists($attrCode, $this->_dropAtrribute)) {
                            $attrCodeFrom = $this->_dropAtrribute[$attrCode];
                        }
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCodeFrom,
                            'null' => true
                            ),
                            array(
                            'attribute' => $attrCodeFrom,
                            //'from'    => $todayDate,
                            'to' => $todayDate,
                            'date' => true
                            )
                            )
                        );
                        // skip where both dates are null.
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCode,
                            'notnull' => true
                            ),
                            array(
                            'attribute' => $attrCodeFrom,
                            'notnull' => true
                            )
                            )
                        );
                        $collection->setFlag('applied_date_ranges_' . $attrCode, true);
                        break;
                    case 'ninrange': // inverted
                        //TO
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCode,
                            'null' => true
                            ),
                            array(
                            'attribute' => $attrCode,
                            'to' => $todayDate,
                            //'to'      => $todayDate,
                            'date' => true
                            )
                            )
                        );
                        // FROM
                        $attrCodeFrom = str_replace('to', 'from', $attrCode);
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCodeFrom,
                            'null' => true
                            ),
                            array(
                            'attribute' => $attrCodeFrom,
                            //'from'    => $todayDate,
                            'from' => $todayDate,
                            'date' => true
                            )
                            )
                        );
                        // skip where both dates are null.
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCode,
                            'notnull' => true
                            ),
                            array(
                            'attribute' => $attrCodeFrom,
                            'notnull' => true
                            )
                            )
                        );
                        $collection->setFlag('applied_date_ranges_' . $attrCode, true);
                        break;
                    case 'xdaysago':
                        $days = ($value == 1) ? 'day' : 'days';
                        $startDate = date('Y-m-d', strtotime('-' . $value . ' ' . $days, strtotime($todayDate)));
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCode,
                            'gteq' => $startDate,
                            'date' => true
                            )
                            )
                        );
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCode,
                            'notnull' => true
                            ),
                            )
                        );
                        break;
                    default:
                        // all other date attributes
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCode,
                            $operator => $value,
                            'date' => true
                            )
                            )
                        );
                        $collection->addAttributeToFilter(
                            array(
                            array(
                            'attribute' => $attrCode,
                            'notnull' => true
                            ),
                            )
                        );
                        break;
                    }
                    break;
                case 'text':
                case 'textarea':
                    $operator = $this->getOperator();
                    switch ($operator) {
                    case '{}':
                        $values = explode(',', $value);
                        $filter = array();
                        foreach ($values as $val) {
                            if (substr($val, 0, 3) == 'rx:') {
                                $value = array('regexp' => substr($val, 3));
                            } else {
                                $val = $this->doWildCard($val);
                                $value = array('like' => $val);
                            }
                                        $filter[] = array('attribute' => $attrCode, $operator => $value);
                        }
                        $collection->addAttributeToFilter($filter);
                        break;
                    case '!{}':
                        $values = explode(',', $value);
                        foreach ($values as $val) {
                            $val = $this->doWildCard($val);
                            $value = array('nlike' => $val);
                            $collection->addAttributeToFilter($attrCode, $value);
                        }
                        break;
                    case '()':
                        $values = explode(',', $value);
                        $filter = array();
                        foreach ($values as $val) {
                            if (substr($val, 0, 3) == 'rx:') {
                                $value = array('regexp' => substr($val, 3));
                            } else {
                                $value = array('eq' => trim($val));
                            }
                                        $filter[] = array('attribute' => $attrCode, $operator => $value);
                        }
                        $collection->addAttributeToFilter($filter);
                        break;
                    case '!()':
                        $values = explode(',', $value);
                        foreach ($values as $val) {
                            $value = array('neq' => $val);
                            $collection->addAttributeToFilter($attrCode, $value);
                        }

                        break;
                    default:
                        $operator = $this->_operatorMap[$this->getOperator()];
                        $collection->addAttributeToFilter($attribute->getAttributeCode(), array($operator => $value));
                        break;
                    }
                    break;
                default:
                    $operator = $this->_operatorMap[$this->getOperator()];
                    if (!in_array($this->getOperator(), array('==|', '!=|')) and $value == $attribute->getDefaultValue()) {
                        $collection->addAttributeToFilter($attribute->getAttributeCode(), array(array($operator => $value), array('null' => true)), 'left');
                    } else {
                        $collection->addAttributeToFilter($attribute->getAttributeCode(), array($operator => $value));
                    }
                    break;
                }
            }
            break;
        }
        $this->getHelper()->debug("Product collection:" . $collection->getSelect());

        return true;
    }

    /**
     * Determine if user had given a wildcard, and if not wildcard the string
     *
     * @param string $value
     * @retun string
     */
    private function doWildCard($value)
    {
        if (strpos($value, '%') === false) {
            return '%' . trim($value) . '%';
        }

        return trim($value);
    }

    /**
     * Correct '==' and '!=' operators
     * Categories can't be equal because product is included categories selected by administrator and in their parents
     *
     * @return string
     */
    public function getOperatorForValidate()
    {
        $op = $this->getOperator();
        if ($this->getInputType() == 'category') {
            if ($op == '==') {
                $op = '{}';
            } elseif ($op == '!=') {
                $op = '!{}';
            }
        }

        return $op;
    }

}

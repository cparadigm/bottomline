<?php

/**
 * ENhanced Product in category grid
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Block_Adminhtml_Catalog_Category_Tab_Product extends Mage_Adminhtml_Block_Catalog_Category_Tab_Product
{

    protected $_productModel;

    public function __construct()
    {
        parent::__construct();
        $this->setId('catalog_category_products');
        $this->setDefaultSort('position');
        $this->setDefaultDir('asc');
        $this->setUseAjax(true);
        $this->_productModel = mage::getModel('catalog/product');
    }

    protected function _prepareCollection()
    {
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('in_category' => 1));
        }
        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('price')
                ->addAttributeToSelect('special_price')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('attribute_set_id')
                ->addAttributeToSelect('type_id')
                ->addAttributeToSelect('visibility')
                ->addStoreFilter($this->getRequest()->getParam('store'))
                ->joinField('position', 'catalog/category_product', 'position', 'product_id=entity_id', 'category_id=' . (int) $this->getRequest()->getParam('id', 0), 'left');

        $select = $collection->getSelect();
        $columns = $select->getPart(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::COLUMNS);
        $columns[] = array('at_position', 'is_dynamic', 'is_dynamic');
        $select->setPart(Zend_Db_Select::COLUMNS, $columns);
        $this->setCollection($collection);
        mage::helper('dyncatprod')->debug("grid collection:" . $collection->getSelect());

        if ($this->getCategory()->getProductsReadonly()) {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
        }

        return call_user_func(array(get_parent_class(get_parent_class($this)), '_prepareCollection'));
    }

    protected function _prepareColumns()
    {
        if (!$this->getCategory()->getProductsReadonly()) {
            $this->addColumn(
                'in_category', array(
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_category',
                'values' => $this->_getSelectedProducts(),
                'align' => 'center',
                'index' => 'entity_id')
            );
        }
        $this->addColumn(
            'entity_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'sortable' => true,
            'width' => '60',
            'index' => 'entity_id'
            )
        );

        $this->addColumn(
            'image', array(
            'header' => Mage::helper('catalog')->__('Image'),
            'index' => 'image',
            'frame_callback' => array($this, 'catalog_product_grid_callback_method_image'),
            'align' => "center",
            'filter' => false
            )
        );

        $this->addColumn(
            'name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name'
            )
        );

        $this->addColumn(
            'sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'width' => '80',
            'index' => 'sku'
            )
        );

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
                ->load()
                ->toOptionHash();

        $this->addColumn(
            'set_name', array(
            'header' => Mage::helper('catalog')->__('Attrib. Set Name'),
            'width' => '100px',
            'index' => 'attribute_set_id',
            'type' => 'options',
            'options' => $sets,
            )
        );

        $this->addColumn(
            'type', array(
            'header' => Mage::helper('catalog')->__('Type'),
            'width' => '60px',
            'index' => 'type_id',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
            )
        );

        $this->addColumn(
            'price', array(
            'header' => Mage::helper('catalog')->__('Price'),
            'type' => 'currency',
            'width' => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index' => 'price'
            )
        );

        $this->addColumn(
            'special_price', array(
            'header' => Mage::helper('catalog')->__('Special Price'),
            'type' => 'currency',
            'width' => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index' => 'special_price'
            )
        );

        $this->addColumn(
            'visibility', array(
            'header' => Mage::helper('catalog')->__('Visibility'),
            'width' => 90,
            'index' => 'visibility',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_visibility')->getOptionArray(),
            )
        );

        $this->addColumn(
            'category_ids', array(
            'header' => Mage::helper('catalog')->__('Category Ids'),
            'index' => 'category_ids',
            'frame_callback' => array($this, 'catalog_product_grid_callback_categories'),
            )
        );

        $this->addColumn(
            'is_dynamic', array(
            'header' => 'Dynamic',
            'index' => 'is_dynamic',
            'frame_callback' => array($this, 'catalog_product_grid_callback_method_dynamic'),
            'align' => "center",
            'filter' => false,
            'width' => '50px'
            )
        );

        $this->addColumn(
            'position', array(
            'header' => Mage::helper('catalog')->__('Position'),
            'width' => '1',
            'type' => 'number',
            'index' => 'position',
            'editable' => !$this->getCategory()->getProductsReadonly(),
            'renderer' => 'dyncatprod/adminhtml_widget_grid_column_renderer_input',
            'header_css_class' => 'a-center',
            'align' => 'center',
            )
        );

        call_user_func(array(get_parent_class(get_parent_class($this)), '_prepareColumns'));
    }

    /**
     * Callback for the grid data.
     * Use this to try and populate any data that is not available.
     *
     * @param  string $value
     * @param  array  $row
     * @param  array  $column
     * @param  bool   $isExport
     * @return string
     */
    public function catalog_product_grid_callback_method_dynamic($value, $row, $column, $isExport)
    {
        if ($value == 1) {
            $value = '<img src="' . $this->getSkinUrl("images/rule_component_apply.gif", array('_secure' => true)) . '"/>';
        } else {
            $value = '';
        }

        return $value;
    }

    /**
     * Callback for the grid data.
     * Use this to try and populate any data that is not available.
     *
     * @param  string $value
     * @param  array  $row
     * @param  array  $column
     * @param  bool   $isExport
     * @return string
     */
    public function catalog_product_grid_callback_method_image($value, $row, $column, $isExport)
    {
        try {
            $imageCacheUrl = Mage::helper('catalog/image')->init($this->_productModel, 'image', $value)->resize(mage::getStoreConfig('dyncatprod/ept/image_size'));

            return '<img src="' . $imageCacheUrl . '" alt="" title=""';
        } catch (Exception $e) {
            return 'No Image';
        }
    }

    /**
     * Callback for the grid data.
     * Use this to try and populate any data that is not available.
     *
     * @param  string $value
     * @param  array  $row
     * @param  array  $column
     * @param  bool   $isExport
     * @return string
     */
    public function catalog_product_grid_callback_categories($value, $row, $column, $isExport)
    {
        try {
            $_product = Mage::getModel('catalog/product')->load($row->getEntityId());
            $ids = $_product->getCategoryIds();

            return implode(",", $ids);
        } catch (Exception $e) {
            return 'None';
        }
    }

}

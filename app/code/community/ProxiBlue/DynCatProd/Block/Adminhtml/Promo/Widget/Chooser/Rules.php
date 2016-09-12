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
class ProxiBlue_DynCatProd_Block_Adminhtml_Promo_Widget_Chooser_Rules extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct($arguments = array())
    {
        parent::__construct($arguments);

        if ($this->getRequest()->getParam('current_grid_id')) {
            $this->setId($this->getRequest()->getParam('current_grid_id'));
        } else {
            $this->setId('rulesChooserGrid_' . $this->getId());
        }

        $form = $this->getJsFormObject();
        $this->setRowClickCallback("$form.chooserGridRowClick.bind($form)");
        $this->setCheckboxCheckCallback("$form.chooserGridCheckboxCheck.bind($form)");
        $this->setRowInitCallback("$form.chooserGridRowInit.bind($form)");
        $this->setDefaultSort('name');
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('collapse')) {
            $this->setIsCollapsed(true);
        }
    }

    /**
     * Retrieve quote store object
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        return Mage::app()->getStore();
    }

    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_rules') {
            $selected = $this->_getSelectedRules();
            if (empty($selected)) {
                $selected = '';
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('rule_id', array('in' => $selected));
            } else {
                $this->getCollection()->addFieldToFilter('rule_id', array('nin' => $selected));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Prepare Catalog Product Collection for attribute SKU in Promo Conditions SKU chooser
     *
     * @return Mage_Adminhtml_Block_Promo_Widget_Chooser_Sku
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalogrule/rule')
                ->getResourceCollection();
        $collection->addWebsitesToResult();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Define Cooser Grid Columns and filters
     *
     * @return Mage_Adminhtml_Block_Promo_Widget_Chooser_Sku
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_rules', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_rules',
            'values' => $this->_getSelectedRules(),
            'align' => 'center',
            'index' => 'rule_id',
            'use_index' => true,
            )
        );

        $this->addColumn(
            'rule_id', array(
            'header' => Mage::helper('catalogrule')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'rule_id',
            )
        );

        $this->addColumn(
            'name', array(
            'header' => Mage::helper('catalogrule')->__('Rule Name'),
            'align' => 'left',
            'index' => 'name',
            )
        );

        $this->addColumn(
            'from_date', array(
            'header' => Mage::helper('catalogrule')->__('Date Start'),
            'align' => 'left',
            'width' => '120px',
            'type' => 'date',
            'index' => 'from_date',
            )
        );

        $this->addColumn(
            'to_date', array(
            'header' => Mage::helper('catalogrule')->__('Date Expire'),
            'align' => 'left',
            'width' => '120px',
            'type' => 'date',
            'default' => '--',
            'index' => 'to_date',
            )
        );

        $this->addColumn(
            'is_active', array(
            'header' => Mage::helper('catalogrule')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'is_active',
            'type' => 'options',
            'options' => array(
                1 => Mage::helper('catalogrule')->__('Active'),
                0 => Mage::helper('catalogrule')->__('Inactive')
            ),
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn(
                'rule_website', array(
                'header' => Mage::helper('catalogrule')->__('Website'),
                'align' => 'left',
                'index' => 'website_ids',
                'type' => 'options',
                'sortable' => false,
                'options' => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(),
                'width' => 200,
                )
            );
        }

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/chooser', array(
                    '_current' => true,
                    'current_grid_id' => $this->getId(),
                    'collapse' => null
            )
        );
    }

    protected function _getSelectedRules()
    {
        $products = $this->getRequest()->getPost('selected', array());

        return $products;
    }

}

<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml new accounts report grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Boardroom_Newsletter_Block_Adminhtml_Report_Customer_Marketing_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('gridMarketing');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('customer/customer')->getCollection()
            ->addNameToSelect()
            ->addAttributeToSelect('email')
            ->addFieldToFilter('send_marketing',1);

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;

    }

    protected function _prepareColumns()
    {
        $this->addColumn('name', array(
            'header'    => Mage::helper('customer')->__('Name'),
            'index'     => 'name'
        ));

        $this->addColumn('email', array(
            'header'    =>Mage::helper('reports')->__('Email'),
            'index'     =>'email',
            'sortable'  => false
        ));

        $this->addExportType('*/*/exportMarketingCsv', Mage::helper('reports')->__('CSV'));

        return parent::_prepareColumns();
    }

}

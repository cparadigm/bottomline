<?php
class Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('orderdetailreportsGrid');
        $this->setTemplate('orderdetailreports/widget/grid.phtml');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setSubReportSize(false);
    }

    protected function _prepareCollection() {
        $this->setCollection(Mage::getModel('sales/order')->getCollection());
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns() {
        $this->addColumn('created_at', array(
            'header'    =>Mage::helper('reports')->__('Order Date'),
            'index'     =>'created_at',
            'type' => 'datetime',
        ));
        $this->addColumn('customer_email', array(
            'header'    =>Mage::helper('sales')->__('Email'),
            'index'     =>'customer_email'
        ));
        $this->addColumn('customer_firstname', array(
            'header'    =>Mage::helper('sales')->__('Name'),
            'index'     =>'customer_firstname',
            'renderer'  => new Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Renderer_CustomerName()
        ));
        $this->addColumn('billing_address', array(
            'header'    =>Mage::helper('sales')->__('Billing Address'),
            'index'     =>'billing_address',
            'renderer'  => new Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Renderer_BillingAddress()
        ));
        $this->addColumn('shipping_address', array(
            'header'    =>Mage::helper('sales')->__('Shipping Address'),
            'index'     =>'shipping_address',
            'renderer'  => new Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Renderer_ShippingAddress()
        ));
        $this->addColumn('applied_rule_ids', array(
            'header'    =>Mage::helper('sales')->__('Discounts'),
            'index'     =>'applied_rule_ids',
            'renderer'  => new Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Renderer_Discount()
        ));
        $this->addColumn('items', array(
            'header'    =>Mage::helper('sales')->__('Items'),
            'index'     =>'items',
            'renderer'  => new Boardroom_OrderDetailReports_Block_Adminhtml_OrderDetailReports_Renderer_Items()
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('boardroom_orderdetailreports')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('boardroom_orderdetailreports')->__('XML'));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return false;
    }

    public function getReport($from, $to) {
        if ($from == '') {
            $from = $this->getFilter('report_from');
        }
        if ($to == '') {
            $to = $this->getFilter('report_to');
        }
        $totalObj = Mage::getModel('reports/totals');
        $totals = $totalObj->countTotals($this, $from, $to);
        $this->setTotals($totals);
        $this->addGrandTotals($totals);
        return $this->getCollection()->getReport($from, $to);
    }

    /**
     * Retrieve Grid data as CSV
     *
     * @return string
     */
    public function getCsv()
    {
        $csv = '';
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();

        $data = array();
        foreach ($this->_columns as $column) {
            if ($column->getExportHeader()!='Items') {
                if (!$column->getIsSystem()) {
                    $data[] = '"'.$column->getExportHeader().'"';
                }
            }
        }
        $csv.= implode(',', $data)."\n";

        foreach ($this->getCollection() as $item) {
            $data = array();
            foreach ($this->_columns as $column) {
                if ($column->getExportHeader()!='Items') {
                    if (!$column->getIsSystem()) {
                        $data[] = '"' . str_replace("<br>","\n",str_replace(array('"', '\\'), array('""', '\\\\'),
                                $column->getRowFieldExport($item))) . '"';
                    }
                } else {
                    $csv.= implode(',', $data)."\n";
                    $data = array();
                    $data[] = '';
                    $data[] = 'Name';
                    $data[] = 'SKU';
                    $data[] = 'Qty';
                    $data[] = 'Price';
                    $data[] = 'Discount(s)';
                    $csv.= implode(',', $data)."\n";
                    $orderItems = $item->getAllItems();
                    if ($orderItems && count($orderItems)>0) {
                        foreach ($orderItems as $orderItem) {
                            $data = array();
                            $data[] = '';
                            $discountHtml = '';
                            $discountIds = $orderItem->getAppliedRuleIds();
                            if ($discountIds && $discountIds != '') {
                                $discountIds = explode(',', $discountIds);
                                $discountHtml = array();
                                foreach ($discountIds as $discountId) {
                                    $coupon = Mage::getModel('salesrule/rule')->load($discountId);
                                    $discountHtml[] = $coupon->getName();
                                }
                                $discountHtml = implode(", ", $discountHtml);
                            }
                            $data[] = '"' . str_replace("<br>","\n",str_replace(array('"', '\\'), array('""', '\\\\'),
                                    $orderItem->getName())) . '"';
                            $data[] = '"' . str_replace("<br>","\n",str_replace(array('"', '\\'), array('""', '\\\\'),
                                    $orderItem->getSku())) . '"';
                            $data[] = '"' . str_replace("<br>","\n",str_replace(array('"', '\\'), array('""', '\\\\'),
                                    (int)$orderItem->getQtyOrdered())) . '"';
                            $data[] = '"' . str_replace("<br>","\n",str_replace(array('"', '\\'), array('""', '\\\\'),
                                    $this->helper('core')->currency($orderItem->getPrice(), true, false))) . '"';
                            $data[] = '"' . str_replace("<br>","\n",str_replace(array('"', '\\'), array('""', '\\\\'),
                                    $discountHtml)) . '"';
                            $csv.= implode(',', $data)."\n";
                        }
                    }
                }
            }
        }

        if ($this->getCountTotals())
        {
            $data = array();
            foreach ($this->_columns as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'),
                            $column->getRowFieldExport($this->getTotals())) . '"';
                }
            }
            $csv.= implode(',', $data)."\n";
        }

        return $csv;
    }
}
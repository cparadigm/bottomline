<?php

require_once "Mage/Adminhtml/controllers/Report/CustomerController.php";
class Boardroom_Newsletter_Adminhtml_Report_CustomerController extends Mage_Adminhtml_Report_CustomerController
{

    public function marketingAction()
    {
        $this->_title($this->__('Reports'))
            ->_title($this->__('Customers'))
            ->_title($this->__('Send Marketing'));

        $this->_initAction()
            ->_setActiveMenu('report/customer/marketing')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Send Marketing'), Mage::helper('adminhtml')->__('Send Marketing'))
            ->_addContent($this->getLayout()->createBlock('boardroom_newsletter/adminhtml_report_customer_marketing'))
            ->renderLayout();
    }

    public function exportMarketingCsvAction()
    {
        $fileName   = 'marketing.csv';
        $content    = $this->getLayout()->createBlock('boardroom_newsletter/adminhtml_report_customer_marketing_grid')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportMarketingExcelAction()
    {
        $fileName   = 'marketing.xml';
        $content    = $this->getLayout()->createBlock('boardroom_newsletter/adminhtml_report_customer_marketing_grid')
            ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

}
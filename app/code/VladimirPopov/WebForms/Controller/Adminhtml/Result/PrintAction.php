<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

use Magento\Framework\App\Filesystem\DirectoryList;

class PrintAction extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        $this->_fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        if($this->getRequest()->getParam('id')){
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result')->load($this->getRequest()->getParam('id'));
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$model->getWebformId());
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result')->load($id);
                if(@class_exists('mPDF')) {
                    $mpdf = @new \mPDF('utf-8', 'A4');
                    @$mpdf->WriteHTML($model->toPrintableHtml());
                    return $this->_fileFactory->create(
                        $model->getPdfFilename(),
                        @$mpdf->Output('','S'),
                        DirectoryList::VAR_DIR
                    );
                }
                $this->messageManager->addWarning(__('Printing is disabled. Please install mPDF library. Run command: composer require mpdf/mpdf'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        return $resultRedirect->setPath('*/form/');
    }
}

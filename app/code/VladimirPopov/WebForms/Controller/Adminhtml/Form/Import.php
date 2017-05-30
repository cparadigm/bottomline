<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Form;

class Import extends \Magento\Backend\App\Action
{
    protected $_workingDirectory;

    protected $_session;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Session\SessionManagerInterface $session
    )
    {
        $this->_session = $session;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $upload = new \Zend_Validate_File_Upload();
        $file = $upload->getFiles('import_form');

        $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Form');

        if ($file) {
            $importData = file_get_contents($file['import_form']['tmp_name']);

            $parse = $model->parseJson($importData);

            if (empty($parse['errors'])) {
                $model->import($importData);
                if ($model->getId()) {
                    $this->messageManager->addSuccessMessage(__('Form "%1" successfully imported.', $model->getName()));
                } else {
                    $this->messageManager->addErrorMessage(__('Unknown error happened during import operation.'));
                }
            } else {
                foreach ($parse['errors'] as $error) {
                    $this->messageManager->addErrorMessage($error);
                }
            }

            if (!empty($parse['warnings'])) {
                foreach ($parse['warnings'] as $warning) {
                    $this->messageManager->addWarningMessage($warning);
                }
            }

            return $this->_redirect('*/*/index');
        }

        $this->messageManager->addErrorMessage(__('The uploaded file contains invalid data.'));

        return $resultRedirect->setPath('*/*/');
    }
}
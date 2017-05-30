<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

use Magento\Backend\App\Action;

class Popup extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultRawFactory;

    protected $_localeDate;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultRawFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    )
    {
        $this->resultRawFactory = $resultRawFactory;
        $this->_coreRegistry = $registry;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        if ($this->getRequest()->getParam('id')) {
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result')->load($this->getRequest()->getParam('id'));
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form' . $model->getWebformId());
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    /**
     * Edit form
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $formId = $this->getRequest()->getParam('webform_id');
        $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Result');
        $modelForm = $this->_objectManager->create('VladimirPopov\WebForms\Model\Form');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            $formId = $model->getWebformId();
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This result no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        if ($formId) {
            $model->setWebformId($formId);
            $modelForm->load($formId);
            if (!$modelForm->getId()) {
                $this->messageManager->addErrorMessage(__('This form no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/form/');
            }
        } else {
            $this->messageManager->addErrorMessage(__('Form identifier is not specified.'));
            /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/form/');
        }

        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        $this->_coreRegistry->register('webforms_result', $model);
        $this->_coreRegistry->register('webforms_form', $modelForm);

        $layout = $this->layoutFactory->create();

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultRaw = $this->resultRawFactory->create();
        $html = $layout->createBlock('VladimirPopov\WebForms\Block\Adminhtml\Result\Popup')
            ->setTemplate('webforms/result/popup.phtml')
            ->toHtml();

        return $resultRaw->setContents($html);
    }
}

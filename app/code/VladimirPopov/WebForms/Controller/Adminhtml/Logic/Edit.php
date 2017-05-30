<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Logic;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
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
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Logic')->load($id);
        $fieldId = $model->getFieldId() ? $model->getFieldId() : $this->getRequest()->getParam('field_id');
        if ($fieldId) {
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Field')->load($fieldId);
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form' . $model->getWebformId());
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('VladimirPopov_WebForms::manage_forms')
            ->addBreadcrumb(__('Web-forms'), __('Web-forms'))
            ->addBreadcrumb(__('Manage Forms'), __('Manage Forms'));
        return $resultPage;
    }

    /**
     * Edit field
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $fieldId = $this->getRequest()->getParam('field_id');
        $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Logic')->setStoreId($this->getRequest()->getParam('store'));
        $modelField = $this->_objectManager->create('VladimirPopov\WebForms\Model\Field')->setStoreId($this->getRequest()->getParam('store'));

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            $fieldId = $model->getFieldId();
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This logic no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        if ($fieldId) {
            $model->setFieldId($fieldId);
            $modelField->load($fieldId);
            if (!$modelField->getId()) {
                $this->messageManager->addErrorMessage(__('This field no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/field/edit', ['id' => $fieldId]);
            }
        } else {
            $this->messageManager->addErrorMessage(__('Field identifier is not specified.'));
            /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/form/');
        }

        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register models to use later in blocks
        $this->_coreRegistry->register('webforms_logic', $model);
        $this->_coreRegistry->register('webforms_field', $modelField);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Logic') : __('New Logic'),
            $id ? __('Edit Logic') : __('New Logic')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Logic'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getName() : __('New Logic'));

        return $resultPage;
    }
}

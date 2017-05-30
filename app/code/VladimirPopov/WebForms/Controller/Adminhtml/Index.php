<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml;

use Magento\Framework\ObjectFactory;
use Magento\Framework\Api\DataObjectHelper;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Validator
     */
    protected $_validator;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /** @var \Magento\Framework\Math\Random */
    protected $_random;

    /**
     * @var \Magento\Framework\Api\ExtensibleDataObjectConverter
     */
    protected $_extensibleDataObjectConverter;

    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Math\Random $random
     * @param \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Math\Random $random,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_fileFactory = $fileFactory;
        $this->_random = $random;
        $this->_extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->layoutFactory = $layoutFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Customer initialization
     *
     * @param string $idFieldName
     * @return string form id
     */
    protected function _initForm($idFieldName = 'id')
    {
        $formId = (int)$this->getRequest()->getParam($idFieldName);
        $form = $this->_objectManager->create('VladimirPopov\WebForms\Model\Form');
        if ($formId) {
            $form->load($formId);
        }

        $this->_coreRegistry->register('webforms_form', $form);
        return $formId;
    }


    /**
     * Customer access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        if($this->getRequest()->getParam('webform_id')){
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$this->getRequest()->getParam('webform_id'));
        }
        if($this->getRequest()->getParam('id')){
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form'.$this->getRequest()->getParam('id'));
        }
        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    public function execute()
    {}
}

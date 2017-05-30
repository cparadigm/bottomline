<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Form;

use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Preview extends Action
{
    protected $_coreRegistry;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    protected $webformsHelper;

    protected $session;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \VladimirPopov\WebForms\Helper\Data $webformsHelper,
        \Magento\Customer\Model\Customer $session,
        \Magento\Framework\Registry $coreRegistry
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->webformsHelper = $webformsHelper;
        $this->session = $session;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_coreRegistry->register('webforms_preview', true);
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}

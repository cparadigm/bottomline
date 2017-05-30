<?php
/**
 * Solwin Infotech
 * Solwin Discount Coupon Code Link Extension
 *
 * @category   Solwin
 * @package    Solwin_Applycoupon
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/ 
 */
namespace Solwin\Applycoupon\Controller\Adminhtml\Couponcode;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Page result factory
     * 
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Page factory
     * 
     * @var \Magento\Backend\Model\View\Result\Page
     */
    protected $_resultPage;

    /**
     * constructor
     * 
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|
     * \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_setPageData();
        return $this->getResultPage();
    }
    /**
     * instantiate result page object
     *
     * @return \Magento\Backend\Model\View\Result\Page|
     * \Magento\Framework\View\Result\Page
     */
    public function getResultPage()
    {
        if ($this->_resultPage === null) {
            $this->_resultPage = $this->_resultPageFactory->create();
        }
        return $this->_resultPage;
    }
    /**
     * set page data
     *
     * @return $this
     */
    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        //$resultPage->setActiveMenu('Solwin_Applycoupon::couponcode');
        $resultPage->getConfig()->getTitle()->prepend((__('Couponcodes')));
        return $this;
    }
}
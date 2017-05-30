<?php
namespace VladimirPopov\WebForms\Observer;

use Magento\Framework\Event\ObserverInterface;

class PredispatchAdminActionControllerObserver implements ObserverInterface
{

    protected $_feedFactory;

    protected $_backendAuthSession;

    public function __construct(
        \VladimirPopov\WebForms\Model\FeedFactory $feedFactory,
        \Magento\Backend\Model\Auth\Session $backendAuthSession
    ) {
        $this->_feedFactory = $feedFactory;
        $this->_backendAuthSession = $backendAuthSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_backendAuthSession->isLoggedIn()) {
            $feedModel = $this->_feedFactory->create();
            /* @var $feedModel \Magento\AdminNotification\Model\Feed */
            $feedModel->checkUpdate();
        }
    }
}

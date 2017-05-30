<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

class CustomersJson extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    protected $_jsonEncoder;

    protected $_customerCollectionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Json\Encoder $jsonEncoder,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        parent::__construct($context);
        $this->_jsonEncoder = $jsonEncoder;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_customerCollectionFactory = $customerCollectionFactory;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Customer::manage');
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $q = $this->getRequest()->getParam('term');
        if($q) {
            $collection = $this->_customerCollectionFactory->create()
                ->addNameToSelect()
                ->addAttributeToSelect('email')
                ->addAttributeToSelect('firstname')
                ->addAttributeToSelect('lastname')
                ->setPageSize(5)
            ;
            $collection->addAttributeToFilter(
                array(
                    array('attribute' => 'email', 'like' => '%' . $q . '%'),
                    array('attribute' => 'name', 'like' => '%' . $q . '%'),
                )
            );
            $customers = [];
            foreach($collection as $customer) {
                $customers[] =
                    [
                        'label' => $customer->getFirstname() . ' ' . $customer->getLastname().' <'.$customer->getEmail().'>',
                        'customerId' => $customer->getId()
                    ];
            }
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $json = $this->_jsonEncoder->encode($customers);
            $resultJson = $this->resultJsonFactory->create();
            return $resultJson->setJsonData($json);
        }
    }
}

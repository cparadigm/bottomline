<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Widget;

use Magento\Framework\View\Element\Template;
use VladimirPopov\WebForms\Model\ResourceModel;
use VladimirPopov\WebForms\Model;

class Result extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_resultsCollection;

    protected $_resultCollectionFactory;

    protected $_htmlPagerBlock;

    protected $_form;

    protected $_formFactory;

    public function __construct(
        Template\Context $context,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        ResourceModel\Result\CollectionFactory $resultCollectionFactory,
        Model\FormFactory $formFactory,
        array $data = []
    )
    {
        $this->_resultCollectionFactory = $resultCollectionFactory;
        $this->_htmlPagerBlock = $htmlPagerBlock;
        $this->_formFactory = $formFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($toolbar = $this->_htmlPagerBlock) {
            $pSize = $this->getData('page_size');
            $toolbar->setAvailableLimit(array($pSize => $pSize, $pSize * 2 => $pSize * 2, $pSize * 3 => $pSize * 3));
            $toolbar->setCollection($this->getResultsCollection());
            $this->addChild('toolbar', $toolbar);
        }
        if ($rating = $this->getLayout()->createBlock('VladimirPopov\WebForms\Block\Result\Rating')) {
            $rating->setData('webform_id', $this->getForm()->getId());
            $this->setChild('rating', $rating);
        }

        return $this;
    }

    /**
     * Get collection of approved submissions for current store view
     *
     * @return \Magento\Framework\Data\Collection | bool
     */
    public function getResultsCollection()
    {
        if (null === $this->_resultsCollection) {
            $this->_resultsCollection = $this->_resultCollectionFactory->create()->setLoadValues(true)
                ->addFilter('store_id', $this->_storeManager->getStore()->getId())
                ->addFilter('webform_id', $this->getForm()->getId())
                ->addFilter('approved', 1)
                ->addOrder('created_time', 'desc');
        }
        return $this->_resultsCollection;
    }

    /**
     * @return \VladimirPopov\WebForms\Model\Form
     */
    public function getForm()
    {
        if (null === $this->_form) {
            $this->_form = $this->_formFactory->create()->load($this->getData('webform_id'));
        }
        return $this->_form;
    }
}
<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Result;

use Magento\Framework\View\Element\Template;
use VladimirPopov\WebForms\Model;

class Rating extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'VladimirPopov_WebForms::webforms/result/rating.phtml';

    protected $_resultFactory;

    protected $_fieldFactory;

    public function __construct(
        Template\Context $context,
        Model\ResultFactory $resultFactory,
        Model\FieldFactory $fieldFactory,
        array $data = [])
    {
        $this->_resultFactory = $resultFactory;
        $this->_fieldFactory = $fieldFactory;
        parent::__construct($context, $data);
    }

    /** @return \VladimirPopov\WebForms\Model\Field */
    public function getField($fieldId){
        return $this->_fieldFactory->create()->setStoreId($this->_storeManager->getStore()->getId())->load($fieldId);
    }

    /**
     * @return array|bool
     */
    public function getSummaryRatings(){
        $webform_id = $this->getData('webform_id');
        $store_id = $this->_storeManager->getStore()->getId();
        if(!$webform_id) return false;

        $summary_ratings = $this->_resultFactory->create()->getResource()->getSummaryRatings($webform_id,$store_id);

        return $summary_ratings;
    }
}
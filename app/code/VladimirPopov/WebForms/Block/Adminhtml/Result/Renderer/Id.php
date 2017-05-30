<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer;

class Id extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_messageCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \VladimirPopov\WebForms\Model\ResourceModel\Message\CollectionFactory $messageCollectionFactory,
        array $data = []
    )
    {
        $this->_messageCollectionFactory = $messageCollectionFactory;
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        $messages = $this->_messageCollectionFactory->create()->addFilter('result_id',$row->getId())->count();
        if($messages) $html = '<div class="result-replied">'.$value.'</div>';
        else $html = '<div class="result-not-replied">'.$value.'</div>';
        return $html;
    }

}
<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Element;

class Customer extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    protected $layout;

    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\Layout $layout,
        $data = []
    ) {
        $this->layout = $layout;
        parent::__construct($factoryElement, $factoryCollection,$escaper, $data);
    }

    public function getElementHtml()
    {
        $config = array(
            'value' => $this->getValue()
        );
        $html = $this->layout->createBlock('\VladimirPopov\WebForms\Block\Adminhtml\Result\Autocomplete\Customer', $this->getName(), ['data' => $config])->toHtml();
        $html .= $this->getAfterElementHtml();

        return $html;
    }


}
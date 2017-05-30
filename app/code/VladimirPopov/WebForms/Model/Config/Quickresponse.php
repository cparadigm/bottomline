<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Config;

class Quickresponse implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $options;

    protected $_quickresponseCollectionFactory;

    public function __construct(
        \VladimirPopov\WebForms\Model\ResourceModel\Quickresponse\CollectionFactory $quickresponseCollectionFactory,
        array $data = []
    )
    {
        $this->_quickresponseCollectionFactory = $quickresponseCollectionFactory;
    }

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray($default = false)
    {
        $options=[];
        $forms = $this->_quickresponseCollectionFactory->create();
        foreach($forms as $form){
            $options[]= [
                'label' => $form->getTitle(),
                'value' => $form->getId(),
            ];
        }
        $this->options = $options;
        return $this->options;
    }
}
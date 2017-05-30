<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Config;

class Form implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $options;

    protected $_formCollectionFactory;

    public function __construct(
        \VladimirPopov\WebForms\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory,
        array $data = []
    )
    {
        $this->_formCollectionFactory = $formCollectionFactory;
    }

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray($default = false)
    {
        $options=[];
        $forms = $this->_formCollectionFactory->create();
        foreach($forms as $form){
            $options[]= [
                'label' => $form->getName(),
                'value' => $form->getId(),
            ];
        }
        $this->options = $options;
        return $this->options;
    }

    public function toGridOptionArray()
    {
        $options=[];
        $forms = $this->_formCollectionFactory->create();
        foreach($forms as $form){
            $options[$form->getId()]= $form->getName();
        }
        $this->options = $options;
        return $this->options;
    }

}
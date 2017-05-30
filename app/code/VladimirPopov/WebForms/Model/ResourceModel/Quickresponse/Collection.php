<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\ResourceModel\Quickresponse;

/**
 * Quickresponse collection
 *
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Constructor
     * Configures collection
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('VladimirPopov\WebForms\Model\Quickresponse', 'VladimirPopov\WebForms\Model\ResourceModel\Quickresponse');
    }

    public function toOptionArray(){
        $collection = $this->addOrder('title','asc');
        $option_array = array();
        foreach($collection as $element)
            $option_array[]= array('value'=>$element->getId(), 'label' => $element->getTitle());
        return $option_array;
    }

}

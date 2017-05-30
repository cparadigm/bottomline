<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Logic;

class Condition
{
    const CONDITION_EQUAL = 'equal';
    const CONDITION_NOTEQUAL = 'notequal';

    public function toOptionArray()
    {
        $options = array();

        $options[]=array('value' => self::CONDITION_EQUAL, 'label' => __('Equal'));
        $options[]=array('value' => self::CONDITION_NOTEQUAL, 'label' => __('NOT equal'));

        return $options;
    }

    public function getOptions()
    {
        $opt = $this->toOptionArray();
        $options = array();
        foreach($opt as $o){
            $options[$o['value']] = $o['label'];
        }

        return $options;
    }
}
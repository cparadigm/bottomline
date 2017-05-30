<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Logic;

class Aggregation
{
    const AGGREGATION_ANY = 'any';
    const AGGREGATION_ALL = 'all';

    public function toOptionArray()
    {
        $options = array();

        $options[]=array('value' => self::AGGREGATION_ANY, 'label' => __('Any value can be checked'));
        $options[]=array('value' => self::AGGREGATION_ALL, 'label' => __('All values should be checked'));

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
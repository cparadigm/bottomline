<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Logic;

class Action
{

    const ACTION_SHOW = 'show';
    const ACTION_HIDE = 'hide';

    public function toOptionArray()
    {
        $options = array();

        $options[]=array('value' => self::ACTION_SHOW, 'label' => __('Show'));
        $options[]=array('value' => self::ACTION_HIDE, 'label' => __('Hide'));

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
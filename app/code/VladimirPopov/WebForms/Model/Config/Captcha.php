<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Config;

class Captcha implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray($default = false)
    {
        if (!$this->options) {
            $this->options = [
                ['value' => 'auto', 'label' => __('Auto (hidden for logged in customers)')],
                ['value' => 'always', 'label' => __('Always on')],
                ['value' => 'off', 'label' => __('Off')],
            ];
        }
        if($default){
            $this->options = array_merge([
                ['value' => 'default' , 'label' => __('Default')],
            ],$this->options);
        }
        return $this->options;
    }
}
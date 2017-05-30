<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Config\Captcha;

class Theme implements \Magento\Framework\Option\ArrayInterface
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
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = [
                ['value' => 'standard' , 'label' => __('Standard')],
                ['value' => 'dark' , 'label' => __('Dark')],
            ];
        }
        return $this->options;
    }
}
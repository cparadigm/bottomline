<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Config\Fieldset;

class Display implements \Magento\Framework\Option\ArrayInterface
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
                ['value' => 'on', 'label' => __('On')],
                ['value' => 'off', 'label' => __('Off')],
            ];
        }

        return $this->options;
    }
}
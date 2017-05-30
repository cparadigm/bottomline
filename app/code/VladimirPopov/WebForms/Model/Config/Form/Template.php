<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Config\Form;

class Template implements \Magento\Framework\Option\ArrayInterface
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
                ['value' => 'VladimirPopov_WebForms::webforms/form/default.phtml', 'label' => __('Default')],
                ['value' => 'VladimirPopov_WebForms::webforms/form/multistep.phtml', 'label' => __('Multistep (display fieldsets as steps)')],
                ['value' => 'VladimirPopov_WebForms::webforms/form/sidebar.phtml', 'label' => __('Sidebar (compact sidebar block)')],
            ];
        }
        return $this->options;
    }
}
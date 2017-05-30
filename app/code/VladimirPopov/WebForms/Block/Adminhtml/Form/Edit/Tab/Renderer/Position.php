<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form\Edit\Tab\Renderer;

class Position extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function render(\Magento\Framework\DataObject $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        return <<<HTML
        <div class="admin__grid-control" style="width: 9rem;">
            <span class="admin__grid-control-value">{$value}</span>
            <input type="text" name="{$this->getNameAttribute($row)}" value="{$value}" class="input-text" style="width: 5rem;"/>
        </div>
HTML;
    }

    public function getNameAttribute(\Magento\Framework\DataObject $row)
    {
        if ($this->getColumn()->getPrefix()) {
            return $this->getColumn()->getPrefix() . '[' . $this->getColumn()->getIndex() . ']' . '[' . $row->getId() . ']';
        }
        return $this->getColumn()->getIndex() . '[' . $row->getId() . ']';
    }
}
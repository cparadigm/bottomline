<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function render(\Magento\Framework\DataObject $row)
    {
        $class = "grid-button-action";
        $edit_url = $this->getUrl('*/*/edit', array('_current' => false, 'id' => $row->getId()));
        $reply_url = $this->getUrl('*/*/reply', array('_current' => true, 'id' => $row->getId()));
        $print_url = $this->getUrl('*/*/printAction', array('id' => $row->getId()));

        $button_print = '<a href="' . $print_url . '" class="' . $class . '"><span>' . __('Print') . '</span></a>';
        $button_edit = '<a href="' . $edit_url . '" class="' . $class . '"><span>' . __('Edit') . '</span></a>';
        $button_reply = '<a href="' . $reply_url . '" class="' . $class . '"><span>' . __('Reply') . '</span></a>';


        return $button_print . $button_edit . $button_reply;
    }

}
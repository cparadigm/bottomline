<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer;

class Value extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_customerFactory;
    
    protected $_fieldFactory;

    protected $_storeManager;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory,
        \Magento\Store\Model\StoreManager $storeManager,
        array $data = []
    )
    {
        $this->_customerFactory = $customerFactory;
        $this->_fieldFactory = $fieldFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $field_id = str_replace('field_', '', $this->getColumn()->getIndex());
        $field = $this->_fieldFactory->create()->load($field_id);
        $value = $row->getData($this->getColumn()->getIndex());
        $html = '';

        if ($field->getType() == 'stars') {
            $html = $this->getStarsBlock($row);
        }
        if ($field->getType() == 'textarea') {
            $html = $this->getTextareaBlock($row);
        }
        if ($field->getType() == 'wysiwyg') {
            $html = $this->getHtmlTextareaBlock($row);
        }
        if (strstr($field->getType(), 'date')) {
            $html = $field->formatDate($value);
        }
        if ($field->getType() == 'email') {
            if($value){
                $websiteId = false;
                try{$websiteId = $this->_storeManager->getStore($row->getStoreId())->getWebsite()->getId();}
                catch(\Magento\Framework\Exception\LocalizedException $e){}
                $customer = $this->_customerFactory->create()->setData('website_id',$websiteId)->loadByEmail($value);
                $html = htmlspecialchars($value);
                if($customer->getId()){
                    $html.= " [<a href='" . $this->getCustomerUrl($customer->getId()) . "' target='_blank'>" . $customer->getName() . "</a>]";
                }
            }
        }

        $html_object = new \Magento\Framework\DataObject(array('html' => $html));

        $this->_eventManager->dispatch('webforms_block_adminhtml_results_renderer_value_render', array('field' => $field, 'html_object' => $html_object, 'value' => $value));

        if ($html_object->getHtml())
            return $html_object->getHtml();

        return nl2br(htmlspecialchars($value));
    }

    public function getTextareaBlock(\Magento\Framework\DataObject $row)
    {
        $field_id = str_replace('field_', '', $this->getColumn()->getIndex());
        $value = htmlspecialchars($row->getData($this->getColumn()->getIndex()));
        if (strlen($value) > 200 || substr_count($value, "\n") > 11) {
            $div_id = 'x_' . $field_id . '_' . $row->getId();
            $onclick = "$('$div_id').style.display ='block'; this.style.display='none';  return false;";
            $pos = strpos($value, "\n", 200);
            if ($pos > 300 || !$pos)
                $pos = strpos($value, " ", 200);
            if ($pos > 300)
                $pos = 200;
            if (!$pos) $pos = 200;
            $html = '<div>' . nl2br(substr($value, 0, $pos)) . '</div>';
            $html .= '<div id="' . $div_id . '" style="display:none">' . nl2br(substr($value, $pos, strlen($value))) . '<br></div>';
            $html .= '<a onclick="' . $onclick . '" style="text-decoration:none;float:right">[' . __('Read more') . ']</a>';
            return $html;
        }
        return nl2br($value);
    }

    public function getHtmlTextareaBlock(\Magento\Framework\DataObject $row)
    {
        $field_id = str_replace('field_', '', $this->getColumn()->getIndex());
        $value = $row->getData($this->getColumn()->getIndex());
        if (strlen(strip_tags($value)) > 200 || substr_count($value, "\n") > 11) {
            $div_id = 'x_' . $field_id . '_' . $row->getId();
            $preview_div_id = 'preview_x_' . $field_id . '_' . $row->getId();
            $onclick = "$('{$preview_div_id}').hide(); $('$div_id').style.display='block'; this.style.display='none';  return false;";
            $html = '<div style="min-width:400px" id="' . $preview_div_id . '">' . $this->htmlCut($value, 200) . '</div>';
            $html .= '<div id="' . $div_id . '" style="display:none;min-width:400px">' . $value . '</div>';
            $html .= '<a onclick="' . $onclick . '" style="text-decoration:none;float:right">[' . __('Read more') . ']</a>';
            return $html;
        }
        return $value;
    }

    public function getStarsBlock(\Magento\Framework\DataObject $row)
    {
        $field_id = str_replace('field_', '', $this->getColumn()->getIndex());
        $field = $this->_fieldFactory->create()->load($field_id);
        $value = (int)$row->getData($this->getColumn()->getIndex());
        $blockwidth = ($field->getStarsCount() * 16) . 'px';
        $width = round(100 * $value / $field->getStarsCount()) . '%';
        $html = "<div class='stars' style='width:$blockwidth'><ul class='stars-bar'><li class='stars-value' style='width:$width'></li></ul></div>";
        return $html;
    }

    public function getCustomerUrl($customerId)
    {

        return $this->getUrl('adminhtml/customer/edit', array('id' => $customerId, '_current' => false));
    }

    public function htmlCut($text, $max_length)
    {
        $tags = array();
        $result = "";

        $is_open = false;
        $grab_open = false;
        $is_close = false;
        $in_double_quotes = false;
        $in_single_quotes = false;
        $tag = "";

        $i = 0;
        $stripped = 0;

        $stripped_text = strip_tags($text);

        while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length) {
            $symbol = $text{$i};
            $result .= $symbol;

            switch ($symbol) {
                case '<':
                    $is_open = true;
                    $grab_open = true;
                    break;

                case '"':
                    if ($in_double_quotes)
                        $in_double_quotes = false;
                    else
                        $in_double_quotes = true;

                    break;

                case "'":
                    if ($in_single_quotes)
                        $in_single_quotes = false;
                    else
                        $in_single_quotes = true;

                    break;

                case '/':
                    if ($is_open && !$in_double_quotes && !$in_single_quotes) {
                        $is_close = true;
                        $is_open = false;
                        $grab_open = false;
                    }

                    break;

                case ' ':
                    if ($is_open)
                        $grab_open = false;
                    else
                        $stripped++;

                    break;

                case '>':
                    if ($is_open) {
                        $is_open = false;
                        $grab_open = false;
                        array_push($tags, $tag);
                        $tag = "";
                    } else if ($is_close) {
                        $is_close = false;
                        array_pop($tags);
                        $tag = "";
                    }

                    break;

                default:
                    if ($grab_open || $is_close)
                        $tag .= $symbol;

                    if (!$is_open && !$is_close)
                        $stripped++;
            }

            $i++;
        }

        while ($tags)
            $result .= "</" . array_pop($tags) . ">";

        return $result;
    }
}
<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Script;

class Tooltip extends \Magento\Framework\View\Element\Template
{
    protected $_form;

    protected $_field;

    protected $_tooltip;

    protected $_template = 'VladimirPopov_WebForms::webforms/scripts/tooltip.phtml';

    protected $_htmlId;

    public function setForm(\VladimirPopov\WebForms\Model\Form $form){
        $this->_form = $form;
        return $this;
    }

    public function getForm(){
        return $this->_form;
    }

    public function setField(\VladimirPopov\WebForms\Model\Field $field){
        $this->_field = $field;
        return $this;
    }

    public function getField(){
        return $this->_field;
    }

    public function setTooltip($tooltip){
        $this->_tooltip = str_replace("'", "\'", trim(preg_replace('/\s+/', ' ', $tooltip)));
        return $this;
    }

    public function getTooltip(){
        return $this->_tooltip;
    }

    public function getHtmlId(){
        if($this->_htmlId) return $this->_htmlId;
        else $this->_htmlId = 'tooltip' . \Magento\Framework\Math\Random::getRandomNumber(6);
        return $this->_htmlId;
    }
}
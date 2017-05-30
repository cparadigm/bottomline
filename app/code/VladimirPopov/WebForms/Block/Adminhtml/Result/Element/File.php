<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Element;

use Magento\Framework\Escaper;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use VladimirPopov\WebForms\Model;

class File extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    protected $_resultFactory;

    protected $fileCollectionFactory;

    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        Model\ResultFactory $resultFactory,
        Model\ResourceModel\File\CollectionFactory $fileCollectionFactory,
        $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('file');
        $this->setExtType('file');
        if (isset($data['value'])) {
            $this->setValue($data['value']);
        }
        $this->fileCollectionFactory = $fileCollectionFactory;
        $this->_resultFactory = $resultFactory;
    }

    public function _getName(){
        return "file_{$this->getData('field_id')}";
    }

    public function removeClass($class)
    {
        $classes = array_unique(explode(' ', $this->getClass()));
        if (false !== ($key = array_search($class, $classes))) {
            unset($classes[$key]);
        }
        $this->setClass(implode(' ', $classes));
        return $this;
    }

    public function getElementHtml()
    {
        $this->addClass('input-file');
        if ($this->getRequired()) {
            $this->removeClass('required-entry');
            if(!$this->getData('value'))
                $this->addClass('required-file');
        }

        $element = sprintf('<input id="%s" name="%s" %s />%s',
            $this->getHtmlId(),
            $this->_getName(),
            $this->serialize($this->getHtmlAttributes()),
            $this->getAfterElementHtml()
        );

        return $this->_getPreviewHtml() . $element . $this->_getDeleteCheckboxHtml();
    }

    protected function _getPreviewHtml(){
        $html = '';
        if($this->getData('result_id')){
            $result = $this->_resultFactory->create()->load($this->getData('result_id'));
            $field_id = $this->getData('field_id');
            $files = $this->fileCollectionFactory->create()
                ->addFilter('result_id', $result->getId())
                ->addFilter('field_id', $field_id);
            /** @var \VladimirPopov\WebForms\Model\File $file */
            foreach ($files as $file){
                if(file_exists($file->getFullPath())){
                    $html .= '<nobr><a href="' . $file->getDownloadLink() . '">' . $file->getName() . '</a> <small>[' . $file->getSizeText() . ']</small></nobr><br>';
                }
            }
        }
        return $html;
    }

    protected function _getDeleteCheckboxHtml()
    {
        $html = '';
        if ($this->getValue() && !$this->getRequired() && !is_array($this->getValue())) {
            $checkboxId = sprintf('%s_delete', $this->getHtmlId());
            $checkbox   = array(
                'type'  => 'checkbox',
                'name'  => str_replace('file_','delete_file_',$this->getName()),
                'value' => '1',
                'class' => 'checkbox',
                'id'    => $checkboxId
            );
            $label      = array(
                'for'   => $checkboxId
            );
            if ($this->getDisabled()) {
                $checkbox['disabled'] = 'disabled';
                $label['class'] = 'disabled';
            }

            $html .= '<div class="' . $this->_getDeleteCheckboxSpanClass() . '">';
            $html .= $this->_drawElementHtml('input', $checkbox) . ' ';
            $html .= $this->_drawElementHtml('label', $label, false) . $this->_getDeleteCheckboxLabel() . '</label>';
            $html .= '</div>';
        }
        return $html;
    }

    protected function _getDeleteCheckboxSpanClass()
    {
        return 'delete-file';
    }

    protected function _getDeleteCheckboxLabel()
    {
        return __('Delete File');
    }

    protected function _drawElementHtml($element, array $attributes, $closed = true)
    {
        $parts = array();
        foreach ($attributes as $k => $v) {
            $parts[] = sprintf('%s="%s"', $k, $v);
        }

        return sprintf('<%s %s%s>', $element, implode(' ', $parts), $closed ? ' /' : '');
    }

}
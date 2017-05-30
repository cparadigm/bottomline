<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Renderer;

use VladimirPopov\WebForms\Model\ResourceModel\File\CollectionFactory;

class File extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_customerFactory;

    protected $_fieldFactory;

    protected $_storeManager;

    protected $fileCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory,
        \VladimirPopov\WebForms\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory,
        \Magento\Store\Model\StoreManager $storeManager,
        array $data = []
    )
    {
        $this->_customerFactory = $customerFactory;
        $this->_fieldFactory = $fieldFactory;
        $this->_storeManager = $storeManager;
        $this->fileCollectionFactory = $fileCollectionFactory;
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());

        $field_id = str_replace('field_', '', $this->getColumn()->getIndex());
        $field = $this->_fieldFactory->create()->load($field_id);

        $files = $this->fileCollectionFactory->create()
            ->addFilter('result_id', $row->getId())
            ->addFilter('field_id', $field_id);

        $html = '';
        /** @var \VladimirPopov\WebForms\Model\File $file */
        foreach ($files as $file) {
            if(file_exists($file->getFullPath())) {
                if ($field->getType() == 'file') {
                    $html .= '<nobr><a href="' . $file->getDownloadLink() . '">' . $file->getName() . '</a> <small>['.$file->getSizeText().']</small></nobr>' .
                        '<br><small>' . __('Type') . ': ' . $file->getMimeType() . '</small>';

                }
                if ($field->getType() == 'image') {
                    $width = $this->_scopeConfig->getValue('webforms/images/grid_thumbnail_width');
                    $height = $this->_scopeConfig->getValue('webforms/images/grid_thumbnail_height');
                    if ($file->getThumbnail($width, $height))
                        $html .= '<a href="' . $file->getDownloadLink() . '"><img src="' . $file->getThumbnail($width, $height) . '"/></a><br>';
                    $html .= '<nobr><a href="' . $file->getDownloadLink() . '">' . $file->getName() . '</a> <small>[' . $file->getSizeText() . ']</small></nobr>';
                }
            }
            else {
                $html .= '<nobr>'. $file->getName() . ' <small>['.$file->getSizeText().']</small></nobr>' .
                    '<br><small>' . __('Type') . ': ' . $file->getMimeType() . '</small>';
            }
        }

        $html_object = new \Magento\Framework\DataObject(array('html' => $html));

        $this->_eventManager->dispatch('webforms_block_adminhtml_results_renderer_value_render', array('field' => $field, 'html_object' => $html_object, 'value' => $value));

        if ($html_object->getHtml())
            return $html_object->getHtml();
    }
}
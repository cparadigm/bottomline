<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Element;

class Image extends \VladimirPopov\WebForms\Block\Adminhtml\Result\Element\File
{
    protected function _getPreviewHtml()
    {
        $html = '';
        if($this->getData('result_id')){
            $result = $this->_resultFactory->create()->load($this->getData('result_id'));
            $field_id = $this->getData('field_id');
            $files = $this->fileCollectionFactory->create()
                ->addFilter('result_id', $result->getId())
                ->addFilter('field_id', $field_id);
            /** @var \VladimirPopov\WebForms\Model\File $file */
            foreach ($files as $file) {
                if(file_exists($file->getFullPath())) {
                    $thumbnail = $file->getThumbnail(100);
                    if ($thumbnail) {
                        $html .= '<div><img src="' . $thumbnail . '"/></div>';
                    }
                    $html .= '<nobr><a href="' . $file->getDownloadLink() . '">' . $file->getName() . '</a> <small>[' . $file->getSizeText() . ']</small></nobr><br>';
                }
            }
        }
        return $html;

    }

}
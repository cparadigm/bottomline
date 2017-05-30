<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Version extends Field
{
    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $_moduleList;

    public function __construct(
        \Magento\Framework\Module\ModuleList $moduleList,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {
        $this->_moduleList = $moduleList;
        parent::__construct($context, $data);
    }
    /**
     * @param AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $moduleInfo = $this->_moduleList->getOne('VladimirPopov_WebForms');
        $version = (string)$moduleInfo['setup_version'];

        return '<div class="label" style="text-align:left;">'.$version.'</div>';
    }
}
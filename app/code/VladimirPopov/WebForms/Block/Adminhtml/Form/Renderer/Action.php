<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var \Magento\Framework\Url
     */
    protected $actionUrlBuilder;

    protected $_storeManager;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Framework\Url $urlBuilder
     * @param array $data
     */
    public function __construct(
        Action\UrlBuilder $actionUrlBuilder,
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        array $data = []
    )
    {
        $this->actionUrlBuilder = $actionUrlBuilder;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Render action
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $store = $this->_storeManager->getStore($this->_scopeConfig->getValue('webforms/general/preview_store'));

        $scope = $store->getId();
        $routePath = 'webforms/form/preview';
        $store = $store->getCode();

        $preview_url = $this->actionUrlBuilder->getUrl(
            $routePath,
            $scope,
            ['_current' => false, 'id' => $row->getId(), '_query' => '___store=' . $store, '_nosid' => true]
        );

        $class = "grid-button-action inline-action";

        $export_url = $this->getUrl('*/*/export', ['_current' => false, 'id' => $row->getId()]);
        $button_export = '<a href="' . $export_url . '" class="' . $class . '"><span>' . __('Export') . '</span></a>';
        $button_preview = '<a href="' . $preview_url . '" target="_blank" class="' . $class . '"><span>' . __('Preview') . '</span></a>';

        return '<div class="inline-buttons">'.$button_export . $button_preview .'</div>';
    }
}

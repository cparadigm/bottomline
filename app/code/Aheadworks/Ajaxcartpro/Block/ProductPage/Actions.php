<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Block\ProductPage;

use Magento\Framework\Registry;

/**
 * Class Actions
 * @package Aheadworks\Ajaxcartpro\Block\ProductPage
 */
class Actions extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @inheritdoc
     */
    protected function _toHtml()
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->coreRegistry->registry('current_product');
        if ($product && $product->getTypeId() == 'aw_giftcard') {
            return '';
        }
        return parent::_toHtml();
    }
}

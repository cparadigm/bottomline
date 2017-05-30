<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Block;

/**
 * Class Config
 * @package Aheadworks\Ajaxcartpro\Block
 */
class Config extends \Magento\Framework\View\Element\Template
{
    /**
     * Get JSON-formatted ACP options
     *
     * @return string
     */
    public function getOptions()
    {
        return \Zend_Json::encode([
            'acpAddToCartUrl' => $this->getUrl('aw_ajaxcartpro/cart/add'),
            'acpGetBlockContentUrl' => $this->getUrl('aw_ajaxcartpro/block/content'),
            'checkoutUrl' => $this->getUrl('checkout', ['_secure' => true])
        ]);
    }
}

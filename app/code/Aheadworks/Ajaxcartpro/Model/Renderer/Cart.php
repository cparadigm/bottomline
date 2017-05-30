<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Model\Renderer;

/**
 * Class Cart
 * @package Aheadworks\Ajaxcartpro\Model\Renderer
 */
class Cart extends AbstractRenderer
{
    /**
     * @inheritdoc
     */
    public function render($layout)
    {
        $block = $layout->getBlock('checkout.cart');
        return $block ? $block->toHtml() : '';
    }
}

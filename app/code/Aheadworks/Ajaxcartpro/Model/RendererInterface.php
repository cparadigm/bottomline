<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Model;

/**
 * Interface RendererInterface
 * @package Aheadworks\Ajaxcartpro\Model
 */
interface RendererInterface
{
    /**
     * Render layout
     *
     * @param \Magento\Framework\View\Layout $layout
     * @return string
     */
    public function render($layout);
}

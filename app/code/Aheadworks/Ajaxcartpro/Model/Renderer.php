<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Model;

use \Aheadworks\Ajaxcartpro\Model\Renderer\Cart;
use \Aheadworks\Ajaxcartpro\Model\Renderer\Confirmation;
use \Aheadworks\Ajaxcartpro\Model\Renderer\Related;
use \Aheadworks\Ajaxcartpro\Model\Renderer\Options;

/**
 * Block Renderer
 * @package Aheadworks\Ajaxcartpro\Model
 */
class Renderer
{
    /**#@+
     * Parts to render
     */
    const PART_OPTIONS = 'options';

    const PART_CONFIRMATION = 'confirmation';

    const PART_RELATED = 'related';

    const PART_CHECKOUT_CART = 'cart';
    /**#@-*/

    /**
     * @var array
     */
    private $partRenderers = [
        self::PART_OPTIONS => Options::class,
        self::PART_CONFIRMATION => Confirmation::class,
        self::PART_RELATED => Related::class,
        self::PART_CHECKOUT_CART => Cart::class
    ];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Render layout
     *
     * @param \Magento\Framework\View\Layout $layout
     * @param string $part
     * @return string
     */
    public function render($layout, $part)
    {
        if (isset($this->partRenderers[$part])
            && $renderer = $this->objectManager->get($this->partRenderers[$part])
        ) {
            return $renderer->render($layout);
        }
        return '';
    }
}

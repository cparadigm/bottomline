<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Model\Renderer;

use Aheadworks\Ajaxcartpro\Block\Ui\Messages;
use Aheadworks\Ajaxcartpro\Block\Ui\Product\Image;
use Aheadworks\Ajaxcartpro\Block\Ui\Product\Reviews;

/**
 * Class AbstractRenderer
 * @package Aheadworks\Ajaxcartpro\Model\Renderer
 */
abstract class AbstractRenderer implements \Aheadworks\Ajaxcartpro\Model\RendererInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Append product image to block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @param \Magento\Catalog\Model\Product $product
     * @return $this
     */
    protected function appendProductImage($block, $layout, $product)
    {
        $imageBlock = $layout->createBlock(
            Image::class,
            'aw_ajaxcartpro.ui.product.image',
            ['data' => ['product' => $product]]
        );
        $block->append($imageBlock, 'product_image');
        return $this;
    }

    /**
     * Append reviews info to block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @param \Magento\Catalog\Model\Product $product
     * @return $this
     */
    protected function appendReviewSummary($block, $layout, $product)
    {
        if ($this->scopeConfig->getValue('aw_ajaxcartpro/add_to_cart_block/display_product_reviews')) {
            $reviewsBlock = $layout->createBlock(
                Reviews::class,
                'aw_ajaxcartpro.ui.product.reviews',
                ['data' => ['product' => $product]]
            );
            $block->append($reviewsBlock, 'product_reviews');
        }
        return $this;
    }

    /**
     * Append messages to block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    protected function appendMessages($block, $layout)
    {
        $messagesBlock = $layout->createBlock(
            Messages::class,
            'aw_ajaxcartpro.ui.messages',
            ['data' => []]
        );
        $block->append($messagesBlock, 'messages');
        return $this;
    }

    /**
     * Render block
     *
     * @param \Magento\Framework\View\Layout $layout
     * @return string
     */
    abstract public function render($layout);
}

<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Block\Ui\Product;

use Magento\Catalog\Block\Product\ReviewRendererInterface;

/**
 * Class Reviews
 * @package Aheadworks\Ajaxcartpro\Block\Ui\Product
 */
class Reviews extends \Magento\Framework\View\Element\Template
{
    /**
     * @var ReviewRendererInterface
     */
    private $reviewRenderer;

    /**
     * @var string
     */
    protected $_template = 'ui/product/reviews.phtml';

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param ReviewRendererInterface $reviewRenderer
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ReviewRendererInterface $reviewRenderer,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->reviewRenderer = $reviewRenderer;
    }

    /**
     * Get reviews summary HTML
     *
     * @return string
     */
    public function getReviewsSummaryHtml()
    {
        if ($product = $this->getProduct()) {
            return $this->reviewRenderer->getReviewsSummaryHtml($product, ReviewRendererInterface::SHORT_VIEW, true);
        }
        return '';
    }
}

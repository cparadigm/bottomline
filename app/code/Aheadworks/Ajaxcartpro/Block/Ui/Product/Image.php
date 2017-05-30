<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Block\Ui\Product;

/**
 * Class Image
 * @package Aheadworks\Ajaxcartpro\Block\Ui\Product
 */
class Image extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    private $productImageBuilder;

    /**
     * @var string
     */
    protected $_template = 'ui/product/image.phtml';

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Block\Product\ImageBuilder $productImageBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Block\Product\ImageBuilder $productImageBuilder,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->productImageBuilder = $productImageBuilder;
    }

    /**
     * Get product image
     *
     * @return string
     */
    public function getProductImage()
    {
        if ($product = $this->getProduct()) {
            return $this->productImageBuilder->setProduct($product)
                ->setImageId('category_page_grid')
                ->create()
                ->toHtml();
        }
        return '';
    }
}

<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Model\Renderer;

use Aheadworks\Ajaxcartpro\Block\Swatches\Product\Renderer\Configurable;

/**
 * Class Options
 * @package Aheadworks\Ajaxcartpro\Model\Renderer
 */
class Options extends AbstractRenderer
{
    /**
     * @var string
     */
    private $blockName = 'product.info.options.wrapper';

    /**
     * 'Configurable options' block name
     *
     * @var string
     */
    private $optionsConfigurableBlockName = 'product.info.options.configurable';

    /**
     * 'Swatch options' block name
     *
     * @var string
     */
    private $optionsSwatchesBlockName = 'product.info.options.swatches';

    /**
     * 'Grouped options' block name
     *
     * @var string
     */
    private $optionsGroupedBlockName = 'product.info.grouped';

    /**
     * 'Bundle options' block name
     *
     * @var string
     */
    private $optionsBundleBlockName = 'product.info.bundle.options';

    /**
     * 'Downloadable options' block name
     *
     * @var string
     */
    private $optionsDownloadableBlockName = 'product.info.downloadable.options';

    /**
     * 'Final price' block name
     *
     * @var string
     */
    private $finalPriceBlockName = 'product.price.final';

    /**
     * 'Bundle price' block name
     *
     * @var string
     */
    private $bundlePriceBlockName = 'product.price.render.bundle.customization';

    /**
     * @inheritdoc
     */
    public function render($layout)
    {
        $block = $layout->getBlock($this->blockName);
        if ($block instanceof \Magento\Framework\View\Element\Template) {
            /** @var \Magento\Framework\View\Element\Template $block */
            $block->setTemplate('Aheadworks_Ajaxcartpro::ui/options.phtml');
            $this->addBlockData($block);
            $this
                ->appendProductImage($block, $layout, $block->getProduct())
                ->appendReviewSummary($block, $layout, $block->getProduct())
                ->appendQty($block, $layout)
                ->appendFinalPrice($block, $layout)
                ->appendJs($block, $layout)
                ->appendConfigurable($block, $layout)
                ->appendSwatches($block, $layout)
                ->appendGrouped($block, $layout)
                ->appendBundle($block, $layout)
                ->appendDownloadable($block, $layout)
                ->appendMessages($block, $layout);
            return $block->toHtml();
        }
        return '';
    }

    /**
     * Add config data to block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @return void
     */
    private function addBlockData($block)
    {
        $block->addData([
            'config_display_short_description' => (bool)$this->scopeConfig->getValue(
                'aw_ajaxcartpro/add_to_cart_block/display_product_short_description'
            )
        ]);
    }

    /**
     * Append js options
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    private function appendJs($block, $layout)
    {
        /** @var \Magento\Framework\View\Element\Template $jsBlock */
        $jsBlock = $layout->createBlock(
            get_class($block),
            'aw_ajaxcartpro.ui.options.form.js',
            ['data' => []]
        );
        $jsBlock->setTemplate('Aheadworks_Ajaxcartpro::ui/options/js.phtml');
        $optionsBlock = $layout->getBlock('product.info.options');
        if ($optionsBlock) {
            /** @var \Magento\Framework\View\Element\Template $priceOptionsJsBlock */
            $priceOptionsJsBlock = $layout->createBlock(get_class($optionsBlock), '', ['data' => []]);
            $priceOptionsJsBlock->setTemplate('Aheadworks_Ajaxcartpro::ui/options/js/price/options.phtml');
            $jsBlock->append($priceOptionsJsBlock);
        }
        $groupedBlock = $layout->getBlock($this->optionsGroupedBlockName);
        if (!$groupedBlock) {
            /** @var \Magento\Framework\View\Element\Template $priceBoxJsBlock */
            $priceBoxJsBlock = $layout->createBlock(get_class($block), '', ['data' => []]);
            $priceBoxJsBlock->setTemplate('Aheadworks_Ajaxcartpro::ui/options/js/pricebox.phtml');
            $jsBlock->append($priceBoxJsBlock);
        }

        $optionsConfigurableBlock = $layout->getBlock($this->optionsConfigurableBlockName);
        $optionsSwatchesBlock = $layout->getBlock($this->optionsSwatchesBlockName);
        if ($optionsConfigurableBlock) {
            $configurableJsBlock = $layout->createBlock(
                \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable::class,
                '',
                ['data' => []]
            );
            $configurableJsBlock->setTemplate('Aheadworks_Ajaxcartpro::ui/options/js/configurable.phtml');
            $jsBlock->append($configurableJsBlock);
        }

        if ($optionsSwatchesBlock) {
            $configurableJsBlock = $layout->createBlock(
                Configurable::class,
                '',
                ['data' => [['product' => $block->getProduct()]]]
            );
            $jsBlock->append($configurableJsBlock);
        }

        $block->append($jsBlock, 'product_form_js');
        return $this;
    }

    /**
     * Append qty
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    private function appendQty($block, $layout)
    {
        /** @var \Magento\Framework\View\Element\Template $qtyBlock */
        $qtyBlock = $layout->createBlock(
            get_class($block),
            'aw_ajaxcartpro.ui.product.qty',
            ['data' => []]
        );
        $qtyBlock->setTemplate('Aheadworks_Ajaxcartpro::ui/options/qty.phtml');
        $block->append($qtyBlock, 'product_qty');
        return $this;
    }

    /**
     * Append final price block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    private function appendFinalPrice($block, $layout)
    {
        $priceBlock = $layout->getBlock($this->finalPriceBlockName);
        if ($priceBlock) {
            $block->append($priceBlock, 'product_price');
        }
        return $this;
    }

    /**
     * Append configurable info block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    private function appendConfigurable($block, $layout)
    {
        $configurableBlock = $layout->getBlock($this->optionsConfigurableBlockName);
        if ($configurableBlock instanceof \Magento\Framework\View\Element\Template) {
            /** @var \Magento\Framework\View\Element\Template $configurableBlock */
            $block->append($configurableBlock, 'product_options_configurable');
        }
        return $this;
    }

    /**
     * Append swatches block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    private function appendSwatches($block, $layout)
    {
        $swatchesBlock = $layout->getBlock($this->optionsSwatchesBlockName);
        if ($swatchesBlock instanceof \Magento\Framework\View\Element\Template) {
            $block->append($swatchesBlock, 'product_options_configurable');
        }
        return $this;
    }

    /**
     * Append grouped info block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    private function appendGrouped($block, $layout)
    {
        $groupedBlock = $layout->getBlock($this->optionsGroupedBlockName);
        if ($groupedBlock instanceof \Magento\Framework\View\Element\Template) {
            /** @var \Magento\Framework\View\Element\Template $groupedBlock */
            $block->unsetChild('product_qty');
            $block->unsetChild('product_price');
            $block->append($groupedBlock, 'product_options_grouped');
        }
        return $this;
    }

    /**
     * Append bundle info block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    private function appendBundle($block, $layout)
    {
        $bundleBlock = $layout->getBlock($this->optionsBundleBlockName);
        if ($bundleBlock instanceof \Magento\Framework\View\Element\Template) {
            /** @var \Magento\Framework\View\Element\Template $bundleBlock */
            $bundleBlock->setTemplate('Aheadworks_Ajaxcartpro::ui/options/bundle.phtml');
            $block->append($bundleBlock, 'product_options_bundle');
        }
        $priceBlock = $layout->getBlock($this->bundlePriceBlockName);
        if ($priceBlock) {
            $block->unsetChild('product_price');
            $block->append($priceBlock, 'product_price');
        }
        return $this;
    }

    /**
     * Append downloadable info block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    private function appendDownloadable($block, $layout)
    {
        $downloadabledBlock = $layout->getBlock($this->optionsDownloadableBlockName);
        if ($downloadabledBlock instanceof \Magento\Framework\View\Element\Template) {
            /** @var \Magento\Framework\View\Element\Template $groupedBlock */
            $block->append($downloadabledBlock, 'product_options_downloadable');
        }
        return $this;
    }
}

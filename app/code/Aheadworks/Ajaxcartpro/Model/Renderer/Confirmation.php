<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Model\Renderer;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class Confirmation
 * @package Aheadworks\Ajaxcartpro\Model\Renderer
 */
class Confirmation extends AbstractRenderer
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param PriceCurrencyInterface $priceCurrency
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($scopeConfig);
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @inheritdoc
     */
    public function render($layout)
    {
        /** @var Template $block */
        $block = $layout->createBlock(
            Template::class,
            'aw_ajaxcartpro.ui.confirmation',
            [
                'data' => [
                    'cart_subtotal' => $this->getCartSubtotal(),
                    'cart_summary' => $this->getCartSummary()
                ]
            ]
        );
        $this
            ->appendProductImage($block, $layout, $this->getProduct())
            ->appendReviewSummary($block, $layout, $this->getProduct())
            ->appendMessages($block, $layout);
        return $block
            ->setTemplate('Aheadworks_Ajaxcartpro::ui/confirmation.phtml')
            ->toHtml();
    }

    /**
     * Get product
     *
     * @return \Magento\Catalog\Model\Product |bool
     */
    private function getProduct()
    {
        $productId = (int)$this->request->getParam('product');
        if ($productId) {
            return $this->productRepository->getById($productId);
        }
        return false;
    }

    /**
     * Get quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    private function getQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    /**
     * Get cart subtotal
     *
     * @return string
     */
    private function getCartSubtotal()
    {
        $totals = $this->getQuote()->getTotals();
        return $this->priceCurrency->format(
            isset($totals['subtotal']) ? $totals['subtotal']->getValue() : 0,
            true,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $this->getQuote()->getStore()
        );
    }

    /**
     * Get cart summary
     *
     * @return float|int|null
     */
    private function getCartSummary()
    {
        $useQty = $this->scopeConfig->getValue(
            'checkout/cart_link/use_qty',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $useQty
            ? $this->getQuote()->getItemsQty()
            : $this->getQuote()->getItemsCount();
    }
}

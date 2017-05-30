<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Model\Plugin;

use \Magento\Quote\Model\Quote as QuoteModel;

/**
 * Class Quote
 * @package Aheadworks\Ajaxcartpro\Model\Plugin
 */
class Quote
{
    /**
     * @var \Aheadworks\Ajaxcartpro\Model\Cart\AddResult
     */
    private $cartAddResult;

    /**
     * @param \Aheadworks\Ajaxcartpro\Model\Cart\AddResult $cartAddResult
     */
    public function __construct(
        \Aheadworks\Ajaxcartpro\Model\Cart\AddResult $cartAddResult
    ) {
        $this->cartAddResult = $cartAddResult;
    }

    /**
     * After addProduct() method plugin
     *
     * @param QuoteModel $quote
     * @param \Magento\Quote\Model\Quote\Item|string $result
     * @return \Magento\Quote\Model\Quote\Item|string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterAddProduct($quote, $result)
    {
        $this->cartAddResult->setAddSuccess(!is_string($result));
        return $result;
    }

    /**
     * After save() method plugin
     *
     * @param QuoteModel $quote
     * @param QuoteModel $result
     * @return QuoteModel
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave($quote, $result)
    {
        $this->cartAddResult->setSaveSuccess(true);
        return $result;
    }

    /**
     * Object saved in the resource model.
     * Not is calling method $quote->save()
     *
     * @param QuoteModel $quote
     * @param QuoteModel $result
     * @return QuoteModel
     */
    public function afterAfterSave($quote, $result)
    {
        return $this->afterSave($quote, $result);
    }
}

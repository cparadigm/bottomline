<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Controller\Cart;

use Aheadworks\Ajaxcartpro\Model\Source\DisplayFor;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Zend\Uri\UriFactory;

/**
 * Class Add
 *
 * @package Aheadworks\Ajaxcartpro\Controller
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class Add extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    private $cartHelper;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\App\Router\PathConfigInterface
     */
    private $pathConfig;

    /**
     * @var \Magento\UrlRewrite\Model\UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Supported product types
     *
     * @var array
     */
    private $supportedProductTypes = [
        \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
        \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE,
        \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE,
        \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE,
        \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE
    ];

    /**
     * Supported product option types
     *
     * @var array
     */
    private $supportedProductOptionTypes = [
        \Magento\Catalog\Model\Product\Option::OPTION_TYPE_FIELD,
        \Magento\Catalog\Model\Product\Option::OPTION_TYPE_AREA,
        \Magento\Catalog\Model\Product\Option::OPTION_TYPE_DROP_DOWN,
        \Magento\Catalog\Model\Product\Option::OPTION_TYPE_RADIO,
        \Magento\Catalog\Model\Product\Option::OPTION_TYPE_CHECKBOX,
        \Magento\Catalog\Model\Product\Option::OPTION_TYPE_MULTIPLE
    ];

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Router\PathConfigInterface $pathConfig
     * @param \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Router\PathConfigInterface $pathConfig,
        \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
        $this->cartHelper = $cartHelper;
        $this->scopeConfig = $scopeConfig;
        $this->pathConfig = $pathConfig;
        $this->urlFinder = $urlFinder;
        $this->storeManager = $storeManager;
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultData = [];
        try {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->productRepository->getById($this->getProductId());
            if (!in_array($product->getTypeId(), $this->supportedProductTypes)
                || $this->hasUnsupportedOptionType($product)
            ) {
                $resultData['reloadUrl'] = $this->getProductViewUrl($product);
            } else {
                $canBeBought = $this->canBeBought($product);
                $displayOptionsFor = $this->scopeConfig->getValue('aw_ajaxcartpro/add_to_cart_block/display_for');
                if ($displayOptionsFor == DisplayFor::PRODUCTS_ALL
                    || ($displayOptionsFor == DisplayFor::PRODUCTS_WITH_REQUIRED_OPTIONS
                        && $product->getTypeInstance()->hasRequiredOptions($product)
                        && !$canBeBought)
                    || ($displayOptionsFor == DisplayFor::PRODUCTS_WITH_ANY_OPTIONS
                        && $product->getTypeInstance()->hasOptions($product)
                        && !$canBeBought)
                ) {
                    $resultData['backUrl'] = $this->getProductViewUrl($product);
                } else {
                    $resultData['backUrl'] = $this->cartHelper->getAddUrl($product);
                }
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $resultData['error'] = $e->getMessage();
        }

        return $this->resultJsonFactory->create()
            ->setData($resultData);
    }

    /**
     * Get product ID
     *
     * @return int|null
     */
    private function getProductId()
    {
        $request = $this->getRequest();
        $productId = $request->getParam('product');
        if (!$productId && $actionUrl = $request->getParam('action_url')) {
            $path = trim(UriFactory::factory($actionUrl)->getPath(), '/');
            $params = explode('/', $path ? $path : $this->pathConfig->getDefaultPath());
            for ($i = 0, $l = sizeof($params); $i < $l; $i++) {
                if ($params[$i] != $this->getBaseFileName()) {
                    if ($params[$i] == 'product' && isset($params[$i + 1])) {
                        $productId = urldecode($params[$i + 1]);
                        break;
                    } else {
                        /** @var \Magento\UrlRewrite\Service\V1\Data\UrlRewrite $urlRewrite */
                        $urlRewrite = $this->urlFinder->findOneByData([
                            UrlRewrite::REQUEST_PATH => $params[$i],
                            UrlRewrite::STORE_ID => $this->storeManager->getStore()->getId(),
                        ]);
                        if ($urlRewrite && $urlRewrite->getEntityType() == 'product') {
                            $productId = $urlRewrite->getEntityId();
                            break;
                        }
                    }
                }
            }
        }

        return $productId;
    }

    /**
     * Get base file name
     *
     * @return string
     */
    private function getBaseFileName()
    {
        $scriptName = $_SERVER['SCRIPT_FILENAME'] ? : $_SERVER['SCRIPT_NAME'];
        return basename($scriptName);
    }

    /**
     * Retrieves product view url
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    private function getProductViewUrl(\Magento\Catalog\Model\Product $product)
    {
        $params = ['_escape' => true];
        if ($product->getTypeInstance()->hasOptions($product)) {
            $params['_query'] = ['options' => 'cart'];
        }
        return $product->getUrlModel()->getUrl($product, $params);
    }

    /**
     * Check whether the given product has unsupported option type
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    private function hasUnsupportedOptionType(\Magento\Catalog\Model\Product $product)
    {
        $options = $product->getOptions();
        if ($options) {
            foreach ($options as $option) {
                if (!in_array($option->getType(), $this->supportedProductOptionTypes)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if request contains all required options to buy the product
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return bool
     */
    private function canBeBought(\Magento\Catalog\Model\Product $product)
    {
        $request = new \Magento\Framework\DataObject($this->getRequest()->getParams());
        $cartCandidates = $product->getTypeInstance()->prepareForCartAdvanced($request, $product);
        if (is_string($cartCandidates) || $cartCandidates instanceof \Magento\Framework\Phrase) {
            return false;
        }
        return true;
    }
}

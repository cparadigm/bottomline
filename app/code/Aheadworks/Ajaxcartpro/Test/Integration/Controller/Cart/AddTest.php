<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Integration\Controller\Cart;

// @codingStandardsIgnoreFile

use Aheadworks\Ajaxcartpro\Model\Source\DisplayFor;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option as ProductOption;
use Magento\Catalog\Model\Product\Url;
use Magento\Checkout\Helper\Cart;
use Magento\Framework\App\Config;
use Magento\Framework\App\MutableScopeConfig;

/**
 * Test class for \Aheadworks\Ajaxcartpro\Controller\Cart\Add
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @magentoAppArea frontend
 */
class AddTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**#@+
     * Redirect types
     */
    const REDIRECT_TO_PRODUCT = 1;

    const REDIRECT_TO_CART = 2;
    /**#@-*/

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var MutableScopeConfig
     */
    private $scopeConfig;

    /**
     * @var string
     */
    private $xmlPathDisplayOptionsFor = 'aw_ajaxcartpro/add_to_cart_block/display_for';

    /**
     * @var Cart
     */
    private $cartHelper;

    /**
     * @var Url
     */
    private $productUrlModel;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->scopeConfig = $this->objectManager->get(MutableScopeConfig::class);
        $this->cartHelper = $this->objectManager->get(Cart::class);
        $this->productUrlModel = $this->objectManager->get(Url::class);
        $this->objectManager->addSharedInstance($this->scopeConfig, Config::class);
        $this->getRequest()->setMethod(\Zend\Http\Request::METHOD_POST);
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_without_options.php
     */
    public function testResultFormat()
    {
        $this->dispatch($this->getUri(1));
        try {
            \Zend_Json::decode($this->getResponse()->getContent());
        } catch (\Zend_Json_Exception $e) {
            $this->fail();
        }
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_without_options.php
     */
    public function testResultWithRedirectAddSimple()
    {
        $this->dispatch($this->getUri(1));
        $this->assertArrayHasKey('backUrl', \Zend_Json::decode($this->getResponse()->getContent()));
    }

    /**
     * @magentoDataFixture Magento/ConfigurableProduct/_files/product_configurable.php
     */
    public function testResultWithRedirectAddConfigurable()
    {
        $this->dispatch($this->getUri(1));
        $this->assertArrayHasKey('backUrl', \Zend_Json::decode($this->getResponse()->getContent()));
    }

    /**
     * @magentoDataFixture Magento/GroupedProduct/_files/product_grouped.php
     */
    public function testResultWithRedirectAddGrouped()
    {
        $this->dispatch($this->getUri(9));
        $this->assertArrayHasKey('backUrl', \Zend_Json::decode($this->getResponse()->getContent()));
    }

    /**
     * @magentoDataFixture Magento/Bundle/_files/product.php
     */
    public function testResultWithRedirectAddBundle()
    {
        $this->dispatch($this->getUri(3));
        $this->assertArrayHasKey('backUrl', \Zend_Json::decode($this->getResponse()->getContent()));
    }

    /**
     * @dataProvider simpleWithoutOptionsDataProvider
     * @magentoDataFixture Magento/Catalog/_files/product_without_options.php
     */
    public function testRedirectAddSimpleWithoutOptions($displayOptionsAt, $redirectTo)
    {
        $this->scopeConfig->setValue($this->xmlPathDisplayOptionsFor, $displayOptionsAt);
        $this->dispatch($this->getUri(1));
        $responseContent = \Zend_Json::decode($this->getResponse()->getContent());
        $this->assertEquals($redirectTo, $this->checkRedirectUrl($responseContent['backUrl']));
    }

    /**
     * @dataProvider simpleWithReqOptionsDataProvider
     * @magentoDataFixture Magento/Catalog/_files/product_without_options.php
     */
    public function testRedirectAddSimpleWithReqOptions($displayOptionsAt, $redirectTo)
    {
        $this->addProductOptions(
            1,
            [
                [
                    'title' => 'test_required_option_code',
                    'type' => ProductOption::OPTION_TYPE_FIELD,
                    'is_require' => true,
                    'sort_order' => 1,
                    'price' => 10.0,
                    'price_type' => 'fixed',
                    'sku' => 'req option sku',
                    'max_characters' => 10
                ]
            ]
        );
        $this->scopeConfig->setValue($this->xmlPathDisplayOptionsFor, $displayOptionsAt);
        $this->dispatch($this->getUri(1));
        $responseContent = \Zend_Json::decode($this->getResponse()->getContent());
        $this->assertEquals($redirectTo, $this->checkRedirectUrl($responseContent['backUrl']));
    }

    /**
     * @dataProvider simpleWithNonReqOptionsDataProvider
     * @magentoDataFixture Magento/Catalog/_files/product_without_options.php
     */
    public function testRedirectAddSimpleWithNonReqOptions($displayOptionsAt, $redirectTo)
    {
        $this->addProductOptions(
            1,
            [
                [
                    'title' => 'test_non_required_option_code',
                    'type' => ProductOption::OPTION_TYPE_FIELD,
                    'is_require' => false,
                    'sort_order' => 1,
                    'price' => 10.0,
                    'price_type' => 'fixed',
                    'sku' => 'non req option sku',
                    'max_characters' => 10
                ]
            ]
        );
        $this->scopeConfig->setValue($this->xmlPathDisplayOptionsFor, $displayOptionsAt);
        $this->dispatch($this->getUri(1));
        $responseContent = \Zend_Json::decode($this->getResponse()->getContent());
        $this->assertEquals($redirectTo, $this->checkRedirectUrl($responseContent['backUrl']));
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_without_options.php
     */
    public function testNewLocAddSimpleWithUnsupportedOptions()
    {
        $this->addProductOptions(
            1,
            [
                [
                    'title' => 'test_unsupported_option_code',
                    'type' => ProductOption::OPTION_TYPE_DATE,
                    'is_require' => false,
                    'sort_order' => 1,
                    'price' => 10.0,
                    'price_type' => 'fixed',
                    'sku' => 'unsupported option sku'
                ]
            ]
        );
        $this->dispatch($this->getUri(1));
        $this->assertArrayHasKey('reloadUrl', \Zend_Json::decode($this->getResponse()->getContent()));
    }

    /**
     * @magentoDataFixture Magento/ConfigurableProduct/_files/product_configurable.php
     */
    public function testRedirectAddConfigurable()
    {
        $this->dispatch($this->getUri(1));
        $responseContent = \Zend_Json::decode($this->getResponse()->getContent());
        $this->assertEquals(self::REDIRECT_TO_PRODUCT, $this->checkRedirectUrl($responseContent['backUrl']));
    }

    /**
     * @magentoDataFixture Magento/GroupedProduct/_files/product_grouped.php
     */
    public function testRedirectAddGrouped()
    {
        $this->dispatch($this->getUri(9));
        $responseContent = \Zend_Json::decode($this->getResponse()->getContent());
        $this->assertEquals(self::REDIRECT_TO_CART, $this->checkRedirectUrl($responseContent['backUrl']));
    }

    /**
     * @magentoDataFixture Magento/Bundle/_files/product.php
     */
    public function testRedirectAddBundle()
    {
        $this->dispatch($this->getUri(3));
        $responseContent = \Zend_Json::decode($this->getResponse()->getContent());
        $this->assertEquals(self::REDIRECT_TO_PRODUCT, $this->checkRedirectUrl($responseContent['backUrl']));
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_without_options.php
     */
    public function testFetchProductIdFromAddToCartActionUrl()
    {
        /** @var Product $product */
        $product = $this->objectManager->create(Product::class)->load(1);
        $this->dispatch($this->getUri(null, $this->cartHelper->getAddUrl($product)));
        $this->assertArrayHasKey('backUrl', \Zend_Json::decode($this->getResponse()->getContent()));
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_without_options.php
     */
    public function testFetchProductIdFromProductViewActionUrl()
    {
        /** @var Product $product */
        $product = $this->objectManager->create(Product::class)->load(1);
        $this->dispatch($this->getUri(null, $this->productUrlModel->getProductUrl($product)));
        $this->assertArrayHasKey('backUrl', \Zend_Json::decode($this->getResponse()->getContent()));
    }

    /**
     * @param int|null $productId
     * @param string|null $actionUrl
     * @return string
     */
    private function getUri($productId = null, $actionUrl = null)
    {
        $uri = 'aw_ajaxcartpro/cart/add';
        $params = [];
        if ($productId) {
            $params[] = 'product=' . $productId;
        }
        if ($actionUrl) {
            $params[] = 'action_url=' . urlencode($actionUrl);
        }
        if (count($params)) {
            $uri .= '?' . implode('&', $params);
        }
        return $uri;
    }

    /**
     * @param string $url
     * @return int
     */
    private function checkRedirectUrl($url)
    {
        return strpos($url, 'checkout/cart/add') === false
            ? self::REDIRECT_TO_PRODUCT
            : self::REDIRECT_TO_CART;
    }

    /**
     * @param int $productId
     * @param array $data
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    private function addProductOptions($productId, $data)
    {
        /** @var Product $product */
        $product = $this->objectManager->create(Product::class)->load(1);
        $product
            ->setCanSaveCustomOptions(true)
            ->setProductOptions($data)
            ->save();
    }

    /**
     * @return array
     */
    public function simpleWithoutOptionsDataProvider()
    {
        return [
            'All products, redirect to product page' => [DisplayFor::PRODUCTS_ALL, self::REDIRECT_TO_PRODUCT],
            'With any options, redirect to cart page' => [DisplayFor::PRODUCTS_WITH_ANY_OPTIONS, self::REDIRECT_TO_CART],
            'With required options, redirect to cart page' => [DisplayFor::PRODUCTS_WITH_REQUIRED_OPTIONS, self::REDIRECT_TO_CART]
        ];
    }

    /**
     * @return array
     */
    public function simpleWithReqOptionsDataProvider()
    {
        return [
            'All products, redirect to product page' => [DisplayFor::PRODUCTS_ALL, self::REDIRECT_TO_PRODUCT],
            'With any options, redirect to product page' => [DisplayFor::PRODUCTS_WITH_ANY_OPTIONS, self::REDIRECT_TO_PRODUCT],
            'With required options, redirect to product page' => [DisplayFor::PRODUCTS_WITH_REQUIRED_OPTIONS, self::REDIRECT_TO_PRODUCT]
        ];
    }

    /**
     * @return array
     */
    public function simpleWithNonReqOptionsDataProvider()
    {
        return [
            'All products, redirect to product page' => [DisplayFor::PRODUCTS_ALL, self::REDIRECT_TO_PRODUCT],
            'With any options, redirect to product page' => [DisplayFor::PRODUCTS_WITH_ANY_OPTIONS, self::REDIRECT_TO_PRODUCT],
            'With required options, redirect to product page' => [DisplayFor::PRODUCTS_WITH_REQUIRED_OPTIONS, self::REDIRECT_TO_CART]
        ];
    }
}

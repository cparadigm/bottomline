<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Integration\Model;

// @codingStandardsIgnoreFile

use Aheadworks\Ajaxcartpro\Model\Processor;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;

/**
 * Test class for \Aheadworks\Ajaxcartpro\Model\Processor
 *
 * @magentoAppArea frontend
 */
class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @var RequestInterface|\Magento\TestFramework\Request
     */
    private $request;

    /**
     * @var ResponseInterface|\Magento\TestFramework\Response
     */
    private $response;

    /**
     * @var \Aheadworks\Ajaxcartpro\Test\Integration\Model\Cart\Stub\AddResult
     */
    private $cartAddResultStub;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->request = $this->objectManager->get(RequestInterface::class);
        $this->response = $this->objectManager->get(ResponseInterface::class);
        $this->cartAddResultStub = $this->objectManager
            ->create(Cart\Stub\AddResult::class);
        $this->processor = $this->objectManager->create(
            Processor::class,
            [
                'cartAddResult' => $this->cartAddResultStub
            ]
        );
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testProcessProductViewResultType()
    {
        $this->request->setParams(['id' => 1]);
        $this->assertInstanceOf(
            Json::class,
            $this->processor->process($this->request, $this->response, Processor::ROUTE_PRODUCT_VIEW)
        );
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testProcessProductViewResultFormat()
    {
        $this->request->setParams(['id' => 1]);
        $result = $this->processor->process($this->request, $this->response, Processor::ROUTE_PRODUCT_VIEW);
        $result->renderResult($this->response);
        $this->assertArrayHasKey('ui', \Zend_Json::decode($this->response->getContent()));
    }

    public function testProcessAddToCartResultType()
    {
        $this->cartAddResultStub->setGetResult(true);
        $this->assertInstanceOf(
            Json::class,
            $this->processor->process($this->request, $this->response, Processor::ROUTE_ADD_TO_CART)
        );
    }

    public function testProcessAddToCartResultFormat()
    {
        $this->cartAddResultStub->setGetResult(true);
        $result = $this->processor->process($this->request, $this->response, Processor::ROUTE_ADD_TO_CART);
        $result->renderResult($this->response);
        $content = \Zend_Json::decode($this->response->getContent());
        $this->assertArrayHasKey('ui', $content);
        $this->assertArrayHasKey('addSuccess', $content);
    }
}

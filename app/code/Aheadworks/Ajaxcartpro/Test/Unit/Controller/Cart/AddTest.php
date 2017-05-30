<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Controller\Cart;

use Aheadworks\Ajaxcartpro\Controller\Cart\Add;
use Aheadworks\Ajaxcartpro\Model\Source\DisplayFor;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Catalog\Model\Product\Url;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;

/**
 * Test for Aheadworks\Ajaxcartpro\Controller\Cart\Add
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AddTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Add
     */
    private $controller;

    /**
     * @var ProductRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productRepositoryMock;

    /**
     * @var ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfigMock;

    /**
     * @var Http|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var JsonFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultJsonFactoryMock;

    /**
     * Setting up mocks
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->productRepositoryMock = $this->getMock(ProductRepositoryInterface::class);
        $this->scopeConfigMock = $this->getMock(ScopeConfigInterface::class);
        $this->requestMock = $this->getMock(Http::class, ['getParams'], [], '', false);
        $this->resultJsonFactoryMock = $this->getMock(JsonFactory::class, ['create'], [], '', false);
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'request' => $this->requestMock,
            ]
        );

        $this->controller = $objectManager->getObject(
            Add::class,
            [
                'context' => $contextMock,
                'scopeConfig' =>$this->scopeConfigMock,
                'productRepository' =>$this->productRepositoryMock,
                'resultJsonFactory' =>$this->resultJsonFactoryMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $product = $this->getMock(Product::class, [], [], '', false);
        $product->expects($this->once())
            ->method('getTypeId')
            ->willReturn(Type::TYPE_SIMPLE);
        $this->productRepositoryMock->expects($this->once())
            ->method('getById')
            ->willReturn($product);
        $product->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);
        $typeInstance = $this->getMock(AbstractType::class, [], [], '', false);
        $product->expects($this->any())
            ->method('getTypeInstance')
            ->willReturn($typeInstance);
        $typeInstance->expects($this->once())
            ->method('hasOptions')
            ->willReturn(false);
        $typeInstance->expects($this->once())
            ->method('prepareForCartAdvanced')
            ->willReturn('error_string');
        $this->requestMock->expects($this->once())
            ->method('getParams')
            ->willReturn([]);
        $this->scopeConfigMock->expects($this->once())->method('getValue')
            ->with('aw_ajaxcartpro/add_to_cart_block/display_for')
            ->willReturn(DisplayFor::PRODUCTS_ALL);
        $urlModel = $this->getMock(Url::class, ['getUrl'], [], '', false);
        $product->expects($this->once())
            ->method('getUrlModel')
            ->willReturn($urlModel);
        $urlModel->expects($this->once())
            ->method('getUrl')
            ->willReturn('https://example.com');
        $jsonMock = $this->getMock(Json::class, ['getUrl'], [], '', false);
        $this->resultJsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($jsonMock);

        $this->assertInstanceOf(Json::class, $this->controller->execute());
    }
}

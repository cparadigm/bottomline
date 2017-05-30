<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Integration\Controller\Plugin;

// @codingStandardsIgnoreFile

use Aheadworks\Ajaxcartpro\Model\Processor;

/**
 * Test class for \Aheadworks\Ajaxcartpro\Controller\Plugin\Action
 *
 * @magentoAppArea frontend
 * @magentoDataFixture Magento/Catalog/_files/product_simple.php
 */
class ActionTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \Aheadworks\Ajaxcartpro\Model\Processor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $processorMock;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->processorMock = $this->getMockBuilder(Processor::class)
            ->disableOriginalConstructor()
            ->setMethods(['process'])
            ->getMock();
        $this->objectManager->addSharedInstance($this->processorMock, Processor::class);
        $this->getRequest()->setMethod(\Zend\Http\Request::METHOD_POST);
    }

    /**
     * @dataProvider processedRoutesDataProvider
     */
    public function testProcessedRoutes($uri)
    {
        $this->processorMock
            ->expects($this->once())
            ->method('process')
            ->willReturn($this->getResponse());
        $this->dispatch($uri);
    }

    /**
     * @dataProvider rejectedRoutesDataProvider
     */
    public function testRejectedRoutes($uri)
    {
        $this->processorMock
            ->expects($this->never())
            ->method('process');
        $this->dispatch($uri);
    }

    /**
     * @return array
     */
    public function processedRoutesDataProvider()
    {
        return [
            ['catalog/product/view?id=1&aw_acp=1'],
            ['checkout/cart/add?product=1&aw_acp=1']
        ];
    }

    /**
     * @return array
     */
    public function rejectedRoutesDataProvider()
    {
        return [
            ['catalog/product/view?id=1'],
            ['checkout/cart/add?product=1'],
            ['aw_ajaxcartpro/cart/add?product=1'],
            ['aw_ajaxcartpro/cart/add?product=1&aw_acp=1'],
            ['catalog/category/view?id=1'],
            ['catalog/category/view?id=1&aw_acp=1'],
            ['cms/index/index'],
            ['cms/index/index?aw_acp=1'],
            ['checkout/cart/index'],
            ['checkout/cart/index?aw_acp=1'],
            ['customer/account/index'],
            ['customer/account/index?aw_acp=1'],
            ['checkout/index/index'],
            ['checkout/index/index?aw_acp=1']
        ];
    }
}

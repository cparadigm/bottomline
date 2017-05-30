<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Model;

use Aheadworks\Ajaxcartpro\Model\Renderer;
use Aheadworks\Ajaxcartpro\Model\Renderer\Cart;
use Aheadworks\Ajaxcartpro\Model\Renderer\Confirmation;
use Aheadworks\Ajaxcartpro\Model\Renderer\Options;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Layout;

/**
 * Test for Aheadworks\Ajaxcartpro\Model\Renderer
 */
class RendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManagerMock;

    /**
     * Setting up mocks
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->objectManagerMock = $this->getMock(ObjectManagerInterface::class);
        $this->renderer = $objectManager->getObject(
            Renderer::class,
            ['objectManager' => $this->objectManagerMock]
        );
    }

    /**
     * Testing of render method
     *
     * @param string $rendererClassName
     * @param string $rendererPart
     * @dataProvider dataProviderRendererParts
     */
    public function testRender($rendererClassName, $rendererPart)
    {
        $layoutMock = $this->getMock(Layout::class, [], [], '', false);
        $rendererMock = $this->getMock($rendererClassName, [], ['render'], '', false);
        $this->objectManagerMock->expects($this->once())
            ->method('get')
            ->with($rendererClassName)
            ->willReturn($rendererMock);
        $renderResult = 'render_result';
        $rendererMock->expects($this->once())
            ->method('render')
            ->with($layoutMock)
            ->willReturn($renderResult);
        $this->assertEquals($renderResult, $this->renderer->render($layoutMock, $rendererPart));
    }

    /**
     * @return array
     */
    public function dataProviderRendererParts()
    {
        return [
            [Options::class, Renderer::PART_OPTIONS],
            [Confirmation::class, Renderer::PART_CONFIRMATION],
            [Cart::class, Renderer::PART_CHECKOUT_CART]
        ];
    }
}

<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Block;

use Aheadworks\Ajaxcartpro\Block\Actions;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Element\Template\Context;

/**
 * Test for Aheadworks\Ajaxcartpro\Block\Actions
 */
class ActionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Actions
     */
    private $actions;

    /**
     * @var ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfigMock;

    /**
     * @var FormKey|\PHPUnit_Framework_MockObject_MockObject
     */
    private $formKeyMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->scopeConfigMock = $this->getMock(ScopeConfigInterface::class);
        $this->formKeyMock = $this->getMock(FormKey::class, ['getFormKey'], [], '', false);
        $contextMock = $this->getMock(Context::class, ['getScopeConfig'], [], '', false);
        $contextMock->expects($this->any())->method('getScopeConfig')->willReturn($this->scopeConfigMock);

        $this->actions = $objectManager->getObject(
            Actions::class,
            [
                'context' => $contextMock,
                'formKey' => $this->formKeyMock,
            ]
        );
    }

    /**
     * Testing of canRedirectToCart method
     */
    public function testCanRedirectToCart()
    {
        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with('checkout/cart/redirect_to_cart')
            ->willReturn(true);
        $this->assertEquals(true, $this->actions->canRedirectToCart());
    }

    /**
     * Testing of getFormKey method
     */
    public function testGetFormKey()
    {
        $this->formKeyMock
            ->expects($this->once())
            ->method('getFormKey')
            ->willReturn('12345678');
        $this->assertJson($this->actions->getFormKey());
    }
}

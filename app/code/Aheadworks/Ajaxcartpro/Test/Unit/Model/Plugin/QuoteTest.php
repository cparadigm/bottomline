<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Model\Plugin;

use Aheadworks\Ajaxcartpro\Model\Cart\AddResult;
use Aheadworks\Ajaxcartpro\Model\Plugin\Quote;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Model\Quote as QuoteModel;
use Magento\Quote\Model\Quote\Item;

/**
 * Test for \Aheadworks\Ajaxcartpro\Model\Plugin\Quote
 */
class QuoteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Quote
     */
    private $quotePlugin;

    /**
     * @var AddResult|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartAddResultMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->cartAddResultMock = $this->getMock(AddResult::class, ['setAddSuccess', 'setSaveSuccess'], [], '', false);
        $this->quotePlugin = $objectManager->getObject(
            Quote::class,
            ['cartAddResult' => $this->cartAddResultMock]
        );
    }

    /**
     * Testing of afterAddProduct method
     *
     * @param \Magento\Quote\Model\Quote\Item|string $result
     * @param bool $success
     * @dataProvider afterAddProductResults
     */
    public function testAfterAddProduct($result, $success)
    {
        $quoteModel = $this->getMock(QuoteModel::class, [], [], '', false);
        $this->cartAddResultMock->expects($this->once())
            ->method('setAddSuccess')
            ->with($success);
        $this->quotePlugin->afterAddProduct($quoteModel, $result);
    }

    /**
     * Data provider for testAfterAddProduct()
     */
    public function afterAddProductResults()
    {
        $result = $this->getMock(Item::class, [], [], '', false);
        return [[$result, true], ['Error message', false]];
    }

    /**
     * Testing of afterSave method
     */
    public function testAfterSave()
    {
        $quoteModel = $this->getMock(QuoteModel::class, [], [], '', false);
        $this->cartAddResultMock->expects($this->once())
            ->method('setSaveSuccess')
            ->with(true);
        $this->quotePlugin->afterSave($quoteModel, $quoteModel);
    }
}

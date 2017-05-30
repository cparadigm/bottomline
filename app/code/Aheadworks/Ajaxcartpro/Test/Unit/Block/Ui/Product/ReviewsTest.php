<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Block\Ui\Product;

use Aheadworks\Ajaxcartpro\Block\Ui\Product\Reviews;
use Magento\Catalog\Block\Product\ReviewRendererInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for Aheadworks\Ajaxcartpro\Test\Unit\Block\Ui\Product\Reviews
 */
class ReviewsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Reviews
     */
    private $reviews;

    /**
     * @var ReviewRendererInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $reviewRendererMock;

    /**
     * Setting up mocks
     */
    protected function setUp()
    {
        $this->reviewRendererMock = $this->getMock(
            ReviewRendererInterface::class,
            ['getReviewsSummaryHtml'],
            [],
            '',
            false
        );
        $productMock = $this->getMock(Product::class, [], [], '', false);
        $objectManager = new ObjectManager($this);
        $this->reviews = $objectManager->getObject(
            Reviews::class,
            [
                'reviewRenderer' => $this->reviewRendererMock,
                'data' => ['product' => $productMock]
            ]
        );
    }

    /**
     * Testing of getReviewsSummaryHtml method
     */
    public function testGetReviewsSummaryHtml()
    {
        $html = 'reviews_html';
        $this->reviewRendererMock->expects($this->once())->method('getReviewsSummaryHtml')
            ->willReturn($html);
        $this->assertEquals($html, $this->reviews->getReviewsSummaryHtml());
    }
}

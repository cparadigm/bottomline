<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Block\Ui\Product;

use Aheadworks\Ajaxcartpro\Block\Ui\Product\Image;
use Magento\Catalog\Model\Product;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Catalog\Block\Product\ImageBuilder;

/**
 * Test for Aheadworks\Ajaxcartpro\Test\Unit\Block\Ui\Product\Image
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Image
     */
    private $image;

    /**
     * @var ImageBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productImageBuilderMock;

    /**
     * Setting up mocks
     */
    protected function setUp()
    {
        $this->productImageBuilderMock = $this->getMock(
            ImageBuilder::class,
            ['setProduct', 'setImageId', 'create', 'toHtml'],
            [],
            '',
            false
        );
        $productMock = $this->getMock(Product::class, [], [], '', false);
        $objectManager = new ObjectManager($this);
        $this->image = $objectManager->getObject(
            Image::class,
            [
                'productImageBuilder' => $this->productImageBuilderMock,
                'data' => ['product' => $productMock]
            ]
        );
    }

    /**
     * Testing of getProductImage method
     */
    public function testGetProductImage()
    {
        $html = 'image_html';
        $this->productImageBuilderMock->expects($this->once())->method('setProduct')->willReturnSelf();
        $this->productImageBuilderMock->expects($this->once())->method('setImageId')
            ->with('category_page_grid')
            ->willReturnSelf();
        $this->productImageBuilderMock->expects($this->once())->method('create')->willReturnSelf();
        $this->productImageBuilderMock->expects($this->once())->method('toHtml')
            ->willReturn($html);
        $this->assertEquals($html, $this->image->getProductImage());
    }
}

<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Model\Source;

use Aheadworks\Ajaxcartpro\Model\Source\DisplayFor;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Ajaxcartpro\Model\Source\DisplayFor
 */
class DisplayForTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DisplayFor
     */
    private $displayFor;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->displayFor = $objectManager->getObject(
            DisplayFor::class,
            []
        );
    }

    /**
     * Testing of toOptionArray method
     */
    public function testToOptionArray()
    {
        $this->assertTrue(is_array($this->displayFor->toOptionArray()));
    }

    /**
     * Testing of getOptionLabelByValue method
     *
     * @param string $expectedLabel
     * @param int $value
     * @dataProvider getOptionLabelByValueDataProvider
     */
    public function testGetOptionLabelByValue($expectedLabel, $value)
    {
        $this->assertEquals($expectedLabel, $this->displayFor->getOptionLabelByValue($value));
    }

    /**
     * @return array
     */
    public function getOptionLabelByValueDataProvider()
    {
        return [
            [__('All products'), DisplayFor::PRODUCTS_ALL],
            [__('Products with required options only'), DisplayFor::PRODUCTS_WITH_REQUIRED_OPTIONS],
            [__('Products with any options'), DisplayFor::PRODUCTS_WITH_ANY_OPTIONS]
        ];
    }
}

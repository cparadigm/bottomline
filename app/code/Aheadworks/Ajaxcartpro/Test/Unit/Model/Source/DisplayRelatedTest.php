<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Model\Source;

use Aheadworks\Ajaxcartpro\Model\Source\DisplayRelated;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Ajaxcartpro\Model\Source\DisplayRelated
 */
class DisplayRelatedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DisplayRelated
     */
    private $displayRelated;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->displayRelated = $objectManager->getObject(
            DisplayRelated::class,
            []
        );
    }

    /**
     * Testing of toOptionArray method
     */
    public function testToOptionArray()
    {
        $this->assertTrue(is_array($this->displayRelated->toOptionArray()));
    }
}

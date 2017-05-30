<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Integration\Model\Cart;

// @codingStandardsIgnoreFile

use Aheadworks\Ajaxcartpro\Model\Cart\AddResult;

/**
 * Test class for \Aheadworks\Ajaxcartpro\Model\Cart\AddResult
 */
class AddResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AddResult
     */
    private $addResult;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->addResult = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(AddResult::class);
    }

    /**
     * @dataProvider addResultDataProvider
     */
    public function testIsSuccess($addSuccess, $saveSuccess, $result)
    {
        $this->addResult->setAddSuccess($addSuccess);
        $this->addResult->setSaveSuccess($saveSuccess);
        $this->assertEquals($result, $this->addResult->isSuccess());
    }

    /**
     * @return array
     */
    public function addResultDataProvider()
    {
        return [
            'Add success, save success' => [true, true, true],
            'Add success, save fail' => [true, false, false],
            'Add fail, save success' => [false, true, false],
            'Add fail, save fail' => [false, false, false]
        ];
    }
}

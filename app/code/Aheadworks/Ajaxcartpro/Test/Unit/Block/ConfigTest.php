<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Block;

use Aheadworks\Ajaxcartpro\Block\Config;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for Aheadworks\Ajaxcartpro\Block\Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Setting up mocks
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->config = $objectManager->getObject(Config::class);
    }

    /**
     * Testing of getOptions method
     */
    public function testGetOptions()
    {
        $this->assertJson($this->config->getOptions());
    }
}

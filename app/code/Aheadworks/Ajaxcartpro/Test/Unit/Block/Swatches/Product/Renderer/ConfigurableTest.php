<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Unit\Block\Swatches\Product\Renderer;

use Aheadworks\Ajaxcartpro\Block\Swatches\Product\Renderer\Configurable;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for Aheadworks\Ajaxcartpro\Test\Unit\Block\Swatches\Product\Renderer\Configurable
 */
class ConfigurableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configurable
     */
    private $configurable;

    /**
     * Setting up mocks
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->configurable = $objectManager->getObject(Configurable::class);
    }

    /**
     * Testing of getSwatchTemplate method
     */
    public function testGetSwatchTemplate()
    {
        $this->assertEquals(
            'Aheadworks_Ajaxcartpro::ui/options/js/swatch.phtml',
            $this->configurable->getSwatchTemplate()
        );
        $newTemplate = 'newTemplateFile';
        $this->configurable->setSwatchTemplate($newTemplate);
        $this->assertEquals($newTemplate, $this->configurable->getSwatchTemplate());
    }

    /**
     * Testing of getConfigurableTemplate method
     */
    public function testGetConfigurableTemplate()
    {
        $this->assertEquals(
            'Aheadworks_Ajaxcartpro::ui/options/js/configurable.phtml',
            $this->configurable->getConfigurableTemplate()
        );
        $newTemplate = 'newTemplateFile';
        $this->configurable->setConfigurableTemplate($newTemplate);
        $this->assertEquals($newTemplate, $this->configurable->getConfigurableTemplate());
    }
}

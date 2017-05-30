<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Block\Swatches\Product\Renderer;

/**
 * Class Configurable
 *
 */
class Configurable extends \Magento\Swatches\Block\Product\Renderer\Configurable
{
    /**
     * @var string
     */
    private $swatchTemplate = 'Aheadworks_Ajaxcartpro::ui/options/js/swatch.phtml';

    /**
     * @var string
     */
    private $configurableTemplate = 'Aheadworks_Ajaxcartpro::ui/options/js/configurable.phtml';

    /**
     * Set swatch template
     *
     * @param string $swatchTemplate
     * @return $this
     */
    public function setSwatchTemplate($swatchTemplate)
    {
        $this->swatchTemplate = $swatchTemplate;
        return $this;
    }

    /**
     * Get swatch template
     *
     * @return string
     */
    public function getSwatchTemplate()
    {
        return $this->swatchTemplate;
    }

    /**
     * Set configurable template
     *
     * @param  string $configurableTemplate
     * @return $this
     */
    public function setConfigurableTemplate($configurableTemplate)
    {
        $this->configurableTemplate = $configurableTemplate;
        return $this;
    }

    /**
     * Get configurable template
     *
     * @return string
     */
    public function getConfigurableTemplate()
    {
        return $this->configurableTemplate;
    }

    /**
     * Get renderer template
     *
     * @return string
     */
    protected function getRendererTemplate()
    {
        return $this->isProductHasSwatchAttribute ?
            $this->swatchTemplate : $this->configurableTemplate;
    }

    /**
     * Get selected swatches values as JSON
     *
     * @return string
     */
    public function getSelectedSwatchesJson()
    {
        $options = $this->getRequest()->getParam('super_attribute');
        return json_encode($options);
    }
}

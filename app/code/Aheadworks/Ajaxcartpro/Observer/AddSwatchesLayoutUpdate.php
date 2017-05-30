<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddSwatchesLayoutUpdate
 * @package Aheadworks\Ajaxcartpro\Observer
 */
class AddSwatchesLayoutUpdate implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * @var string
     */
    private $swatchesHandle = 'aw_ajaxcartpro_swatches';

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Module\Manager $moduleManager
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Module\Manager $moduleManager
    ) {
        $this->request = $request;
        $this->moduleManager = $moduleManager;
    }

    /**
     * Add swatches
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (
            !in_array(
                strtolower($this->request->getFullActionName()),
                [
                    'catalog_category_view',
                    'catalog_product_view_type_configurable'
                ]
            )
            && $this->moduleManager->isOutputEnabled('Magento_Swatches')
        ) {
            $observer->getEvent()
                ->getLayout()
                ->getUpdate()
                ->addHandle($this->swatchesHandle);
        }
    }
}

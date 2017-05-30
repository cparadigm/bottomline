<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Block\Ui;

use Aheadworks\Ajaxcartpro\Model\Source\DisplayRelated;
use Aheadworks\Autorelated\Api\BlockRepositoryInterface as ArpBlockRepositoryInterface;
use Aheadworks\Wbtab\Model\ResourceModel\Product\Collection as WbtabProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\Link\Product\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Module\Manager as ModuleManager;

/**
 * Class Related
 *
 * @package Aheadworks\Ajaxcartpro\Block\Ui
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Related extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var string
     */
    protected $_template = 'ui/related.phtml';

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Related products collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    private $itemCollection;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ModuleManager $moduleManager
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ModuleManager $moduleManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->moduleManager = $moduleManager;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    /**
     * Prepare related items data
     *
     * @return \Aheadworks\Ajaxcartpro\Block\Ui\Related
     */
    private function prepareData()
    {
        $relatedType = $this->_scopeConfig->getValue('aw_ajaxcartpro/additional/related_products_type');

        if (!($product = $this->getProduct()) || $relatedType == DisplayRelated::NONE) {
            return $this;
        }
        switch ($relatedType) {
            case DisplayRelated::NATIVE_CROSS_SELLS:
                $this->itemCollection = $this->getNativeCrossSells($product);
                break;
            case DisplayRelated::ARP_BY_AHEADWORKS:
                $this->itemCollection = $this->getAwArpProducts();
                break;
            case DisplayRelated::WBTAB_BY_AHEADWORKS:
                $this->itemCollection = $this->getAwWbtabProducts($product->getId());
                break;
        }
        return $this;
    }

    /**
     * Get native cross-sells product collection
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product[]
     */
    private function getNativeCrossSells($product)
    {
        /* @var $product \Magento\Catalog\Model\Product */

        $itemCollection = $product->getCrossSellProductCollection()->addAttributeToSelect(
            $this->_catalogConfig->getProductAttributes()
        )->setPositionOrder()->addStoreFilter();

        foreach ($itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $itemCollection;
    }

    /**
     * Get AW ARP product collection
     *
     * @return \Magento\Catalog\Model\Product[]|null
     */
    private function getAwArpProducts()
    {
        if (!$this->moduleManager->isEnabled('Aheadworks_Autorelated')) {
            return null;
        }
        $arpBlocks = ObjectManager::getInstance()
            ->get(ArpBlockRepositoryInterface::class)
            ->getList(\Aheadworks\Autorelated\Model\Source\Type::PRODUCT_BLOCK_TYPE)
            ->getItems();
        $arpBlock = array_shift($arpBlocks);
        if (is_object($arpBlock)) {
            $arpProductIds = $arpBlock->getProductIds();
            /** @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection $itemCollection */
            $itemCollection = $this->collectionFactory->create();
            $itemCollection->addIdFilter($arpProductIds)
                ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                ->setPositionOrder()
                ->addStoreFilter();

            foreach ($itemCollection as $product) {
                $product->setDoNotUseCategoryId(true);
            };
            $this->setData('awarp_rule', $arpBlock->getRule()->getId());
            return $itemCollection;
        }
        return null;
    }

    /**
     * Get AW WBTAB product collection
     *
     * @param int $productId
     * @return \Magento\Catalog\Model\Product[]|null
     */
    private function getAwWbtabProducts($productId)
    {
        if (!$this->moduleManager->isEnabled('Aheadworks_Wbtab')) {
            return null;
        }

        $quoteProductIds = $this->getQuoteProductIds();

        $wbtabCollection = ObjectManager::getInstance()
            ->get(WbtabProductCollection::class)
            ->addAttributeToSelect('required_options')
            ->addWbtabFilter($productId, $quoteProductIds)
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes());

        foreach ($wbtabCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        return $wbtabCollection;
    }

    /**
     * Get quote product IDs
     *
     * @return int[]
     */
    private function getQuoteProductIds()
    {
        $quoteProductIds = [];
        if ($quote = $this->checkoutSession->getQuote()) {
            foreach ($quote->getAllVisibleItems() as $quoteItem) {
                $quoteProductIds[] = $quoteItem->getProductId();
            }
        }
        return $quoteProductIds;
    }

    /**
     * Before rendering html process
     * Prepare items collection
     *
     * @return \Aheadworks\Ajaxcartpro\Block\Ui\Related
     */
    protected function _beforeToHtml()
    {
        $this->prepareData();
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve related items collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getItems()
    {
        return $this->itemCollection;
    }
}

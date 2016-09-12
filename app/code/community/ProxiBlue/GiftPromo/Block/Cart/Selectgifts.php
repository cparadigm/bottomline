<?php

/**
 * Render block to allow gift selection
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_GiftPromo
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_GiftPromo_Block_Cart_Selectgifts
    extends Mage_Checkout_Block_Cart_Abstract
{

    /**
     * Rules used
     * @var array
     */
    protected $_rules = array();

    /**
     * The price block
     * @var array
     */
    protected $_priceBlock = array();

    /**
     * The template for price block rendering
     * @var string
     */
    protected $_priceBlockDefaultTemplate = 'catalog/product/price.phtml';

    /**
     * Price block object
     * @var string
     */
    protected $_block = 'catalog/product_price';

    protected function _construct()
    {
        parent::_construct();
        $this->addData(array(
            'cache_lifetime' => null,
            'cache_tags' => array(
                Mage_Cms_Model_Block::CACHE_TAG)
        ));
    }

    /**
     * Prepare the data for display
     */
    protected function _prepareData()
    {
        try {
            $this->_rules = array();
            $quote = $this->getQuote();
            $address = $quote->getShippingAddress();
            // check rule based gifts
            $store = Mage::app()->getStore($quote->getStoreId());
            $validator = Mage::getSingleton('giftpromo/promo_validator');
            $validator->init($store->getWebsiteId(),
                $quote->getCustomerGroupId(),
                $quote->getCouponCode());
            $rules = Mage::getModel('giftpromo/promo_rule')->getCollection();
            $this->_itemCollection = new Varien_Data_Collection;

            foreach ($rules as $rule) {
                $ruleObject = Mage::getModel('giftpromo/promo_rule')
                    //->setSkipSelectedGift(true)
                    ->load($rule['rule_id']);
                if ($validator->canProcessRule($ruleObject,
                        $address)) {
                    if ($ruleObject->getAllowGiftSelection() && $ruleObject->validate($quote)) {
                        $ruleObject->setForName($ruleObject->getRuleName());
                        $ruleObject->setSelectModeText($this->__('select one of the following products'));
                        try {
                            //how many gifts can be added on this rule....
                            $validItems = $ruleObject->validateCount($quote);
                            $originalId = $ruleObject->getId();
                            foreach ($validItems as $itemCounted) {
                                $itemName = $itemCounted->getName();
                                $copiedRuleObject = clone $ruleObject;
                                $copiedRuleObject->setParentName($itemName);
                                $copiedRuleObject->setId($originalId . '_' . $itemCounted->getId());
                                $this->_itemCollection->addItem($copiedRuleObject);
                            }
                        } catch (Exception $e) {
                            // fail silently, as the item already exists as a gift, thus don't add it again
                        }
                    }
                }
                if ($rule->getStopRulesProcessing()) {
                    break;
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
            if (Mage::getIsDeveloperMode()) {
                die($e->getMessage());
            }
        }
    }

    /**
     * Before html call
     * @return object
     */
    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    /**
     * Get the items in this collection
     * @return object
     */
    public function getItems()
    {
        return $this->_itemCollection;
    }

    /**
     * Retrive add to cart url
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getAddToCartUrl($product)
    {
        return Mage::getUrl('giftpromo/cart/addgift');
    }

    /**
     *  get cart url
     * @return string
     */
    public function getCartUrl()
    {
        return Mage::getUrl('checkout/cart/');
    }

    /**
     * Is the product selected ?
     *
     * @param object $product
     * @return boolean
     */
    public function isCurrentSelected($product, $giftItemKey)
    {
        $currentSelectedGifts = Mage::helper('giftpromo')->getCurrentSelectedGifts();
        if (array_key_exists($giftItemKey,
                $currentSelectedGifts)) {
            if ($product->getId() == $currentSelectedGifts[$giftItemKey]) {
                return true;
            }
        }
        return false;
    }

    /**
     * Are there products selected.
     *
     * @param object $product
     * @param object $giftItem
     * @return boolean
     */
    public function hasCurrentSelected($product, $parentItem = false, $giftItemKey)
    {
        if ($product->getGiftRuleId()) {
            $currentSelectedGifts = Mage::helper('giftpromo')->getCurrentSelectedGifts();
            if (array_key_exists($giftItemKey,
                    $currentSelectedGifts)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns product price block html
     *
     * @param Mage_Catalog_Model_Product $product
     * @param boolean $displayMinimalPrice
     * @param string $idSuffix
     * @return string
     */
    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix = '')
    {
        $type_id = $product->getTypeId();
        if (!mage::helper('giftpromo')->isPre16()) {
            if (Mage::helper('catalog')->canApplyMsrp($product)) {
                $realPriceHtml = $this->_preparePriceRenderer($type_id)
                    ->setProduct($product)
                    ->setDisplayMinimalPrice($displayMinimalPrice)
                    ->setIdSuffix($idSuffix)
                    ->toHtml();
                $product->setAddToCartUrl($this->getAddToCartUrl($product));
                $product->setRealPriceHtml($realPriceHtml);
                $type_id = $this->_mapRenderer;
            }
        }

        return $this->_preparePriceRenderer($type_id)
                ->setProduct($product)
                ->setDisplayMinimalPrice($displayMinimalPrice)
                ->setIdSuffix($idSuffix)
                ->toHtml();
    }

    /**
     * Prepares and returns block to render some product type
     *
     * @param string $productType
     * @return Mage_Core_Block_Template
     */
    public function _preparePriceRenderer($productType)
    {
        return $this->_getPriceBlock($productType)
                ->setTemplate($this->_getPriceBlockTemplate($productType))
                ->setUseLinkForAsLowAs($this->_useLinkForAsLowAs);
    }

    /**
     * get the block to be used for rendering the price display
     *
     * @param object $productTypeId
     * @return object
     */
    protected function _getPriceBlock($productTypeId)
    {
        if (!isset($this->_priceBlock[$productTypeId])) {
            $block = $this->_block;
            if (isset($this->_priceBlockTypes[$productTypeId])) {
                if ($this->_priceBlockTypes[$productTypeId]['block'] != '') {
                    $block = $this->_priceBlockTypes[$productTypeId]['block'];
                }
            }
            $this->_priceBlock[$productTypeId] = $this->getLayout()->createBlock($block);
        }
        return $this->_priceBlock[$productTypeId];
    }

    /**
     * Get the template to be used with price rendering for product type
     * @param int $productTypeId
     * @return string
     */
    protected function _getPriceBlockTemplate($productTypeId)
    {
        if (isset($this->_priceBlockTypes[$productTypeId])) {
            if ($this->_priceBlockTypes[$productTypeId]['template'] != '') {
                return $this->_priceBlockTypes[$productTypeId]['template'];
            }
        }
        return $this->_priceBlockDefaultTemplate;
    }

    /**
     * Return true if product has options
     *
     * @return bool
     */
    public function hasOptions($product)
    {
        if ($product->getTypeInstance(true)->hasOptions($product)) {
            return true;
        }
        return false;
    }

    /**
     * Generate a unique id for this product, and assign to product model.
     * It can then be used throughout all child blocks as well.
     * This is required to keep form and element id's unique, so the sameproduct can appear multiple times, and
     * not clash with each other.
     *
     * @param object $product
     * @return string
     */
    public function addUniqueId($product)
    {
        if (is_null($product->getGiftUid())) {
            $product->setGiftUid(rand(0,
                    10000000000) . md5(uniqid(rand(0,
                            10000000000) . '_',
                        true)) . rand(0,
                    10000000000));
        }
        return $product;
    }

    /**
     * Check for Out Of Stock items and filter accordingly
     * //also filterout any products that are already in the cart
     *
     * @param object $object the product object
     *
     * @return array
     */
    public function filterItems($object)
    {
        $helper = mage::helper('giftpromo');
        if (Mage::getStoreConfig('giftpromo/cart/oos_enabled')) {
            return $object->getItems();
        } else {
            $_giftItems = array();
            foreach ($object->getItems() as $_item) {
                // only display products which are in stock
                if ($_item->isSaleable()) {
                    $_giftItems[] = $_item;
                }
            }
        }
        return $_giftItems;
    }

    /**
     * Build html for options wrapper
     *
     * @param object $_item
     * @return string
     */
    public function getOptionsHtml($_item)
    {
        $childBlock = $this->getChild($_item->getTypeId() . '_product_options_wrapper');
        if (is_object($childBlock)) {
            return $childBlock->setProduct($_item)->toHtml();
        }
        return '';
    }

    public function getCacheLifetime()
    {
        return null;
    }

    /**
     * Load block html from cache storage
     *
     * @return string | false
     */
    protected function _loadCache()
    {
        return false;
    }

    /**
     * Save block content to cache storage
     *
     * @param string $data
     * @return Mage_Core_Block_Abstract
     */
    protected function _saveCache($data)
    {
        return false;
    }

    public function canDisplay($_object, $giftItemKey)
    {
        //if ($_object->getItems()->getSize() == 1) {
            // don't display gift selection if something is selected
            $_giftItems = $this->filterItems($_object);
            foreach ($_giftItems as $itemKey => $_item) {
                if ($this->isCurrentSelected($_item,
                        $giftItemKey)) {
                    return false;
                }
            }
        //}
        return true;
    }

    /**
     * Get product thumbnail image
     *
     * @return Mage_Catalog_Model_Product_Image
     */
    public function getProductThumbnail($item)
    {
        $product = false;
        if ($option = $item->getOptionByCode('simple_product')) {
            $product = $option->getProduct();
        }

        if (!$product || !$product->getData('thumbnail')
            || ($product->getData('thumbnail') == 'no_selection')
            || (Mage::getStoreConfig(self::CONFIGURABLE_PRODUCT_IMAGE) == self::USE_PARENT_IMAGE)) {
            $product = $item;
        }
        return $this->helper('catalog/image')->init($product, 'thumbnail');
    }

    public function getSelectGiftMessage($_object) {
        $parentName = $_object->getParentName();
        $messageTemplate = mage::getStoreConfig('giftpromo/cart/selectgifts_message');
        if(empty($messageTemplate)) {
            return $this->__("<h2>You qualify for a gift on '%s', %s:</h2>", $parentName, $_object->getSelectModeText());
        } else {
            $messageTemplate = str_replace('{PRODUCT_NAME}',$parentName,$messageTemplate);
            $messageTemplate = str_replace('{RULE_NAME}',$_object->getRuleName(),$messageTemplate);
            $messageTemplate = str_replace('{RULE_DESCRIPTION}',$_object->getDescription(),$messageTemplate);
            return $messageTemplate;
        }
    }

}

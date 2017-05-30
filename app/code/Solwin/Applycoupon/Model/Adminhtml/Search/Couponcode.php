<?php
/**
 * Solwin Infotech
 * Solwin Discount Coupon Code Link Extension
 *
 * @category   Solwin
 * @package    Solwin_Applycoupon
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/ 
 */
namespace Solwin\Applycoupon\Model\Adminhtml\Search;

use Solwin\Applycoupon\Model\ResourceModel\Couponcode\CollectionFactory;

class Couponcode extends \Magento\Framework\DataObject
{
    /**
     * Couponcode Collection factory
     * 
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Backend data helper
     * 
     * @var \Magento\Backend\Helper\Data
     */
    protected $_adminhtmlData;

    /**
     * constructor
     * 
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Backend\Helper\Data $adminhtmlData
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        \Magento\Backend\Helper\Data $adminhtmlData
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_adminhtmlData     = $adminhtmlData;
        parent::__construct();
    }

    /**
     * Load search results
     *
     * @return $this
     */
    public function load()
    {
        $result = [];
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($result);
            return $this;
        }

        $query = $this->getQuery();
        $collection = $this->_collectionFactory->create()
            ->addFieldToFilter('rule_name', ['like' => '%'.$query.'%'])
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();

        foreach ($collection as $couponcode) {
            $result[] = [
                'id' => 'solwin_applycoupon_couponcode/1/' 
                . $couponcode->getId(),
                'type' => __('Couponcode'),
                'name' => $couponcode->getRule_name(),
                'description' => $couponcode->getRule_name(),
                'form_panel_title' => __(
                    'Couponcode %1',
                    $couponcode->getRule_name()
                ),
                'url' => $this->_adminhtmlData
                    ->getUrl
                    (
                            'solwin_applycoupon/couponcode/edit', 
                            ['couponcode_id' => $couponcode->getId()]
                            ),
            ];
        }

        $this->setResults($result);

        return $this;
    }
}
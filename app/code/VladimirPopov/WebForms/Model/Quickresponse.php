<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Quickresponse extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    /**
     * Quickresponse cache tag
     */
    const CACHE_TAG = 'webforms_quickresponse';

    /**
     * @var string
     */
    protected $_cacheTag = 'webforms_quickresponse';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'webforms_quickresponse';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('VladimirPopov\WebForms\Model\ResourceModel\Quickresponse');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData('id');
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getId();
    }

}

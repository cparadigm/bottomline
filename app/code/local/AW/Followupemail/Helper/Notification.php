<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Followupemail
 * @version    3.6.1
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Followupemail_Helper_Notification extends Mage_Core_Helper_Data
{
    const OLDER_EMAIL_PERIOD = 3;

    public function getNotification()
    {
        $notifications = array();
        if ($this->isQueueHasOlderEmail()) {
            $notifications[] = $this->__(
                'Follow Up Email queue has unsent items older than %s days. Please check your cron settings.',
                self::OLDER_EMAIL_PERIOD
            );
        }
        return $notifications;
    }

    public function isQueueHasOlderEmail()
    {
        $date = new Zend_Date();
        $date->subDay(self::OLDER_EMAIL_PERIOD);
        $queue = Mage::getModel('followupemail/queue')->getCollection()
            ->addFieldToFilter('scheduled_at', array('lt' => $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT)))
            ->addFieldToFilter('status', AW_Followupemail_Model_Source_Queue_Status::QUEUE_STATUS_READY);
        $result = true;
        if ($queue->getSize() === 0) {
            $result = false;
        }
        return $result;
    }

}
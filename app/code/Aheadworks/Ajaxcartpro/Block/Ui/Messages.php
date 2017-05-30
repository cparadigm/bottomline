<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Block\Ui;

use Magento\Framework\Message\MessageInterface;

/**
 * Class Messages
 * @package Aheadworks\Ajaxcartpro\Block\Ui
 */
class Messages extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @var \Magento\Framework\Message\Collection|null
     */
    private $messages = null;

    /**
     * @var string
     */
    protected $_template = 'ui/messages.phtml';

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->messageManager = $messageManager;
    }

    /**
     * Get messages
     *
     * @return \Magento\Framework\Message\Collection|null
     */
    private function getMessages()
    {
        if ($this->messages === null) {
            $this->messages = $this->messageManager->getMessages(true);
        }
        return $this->messages;
    }

    /**
     * Get error messages
     *
     * @return \Magento\Framework\Message\MessageInterface[]
     */
    public function getErrorMessages()
    {
        return $this->getMessages()->getItemsByType(MessageInterface::TYPE_ERROR);
    }

    /**
     * Get notice messages
     *
     * @return \Magento\Framework\Message\MessageInterface[]
     */
    public function getNoticeMessages()
    {
        return $this->getMessages()->getItemsByType(MessageInterface::TYPE_NOTICE);
    }

    /**
     * Get success messages
     *
     * @return \Magento\Framework\Message\MessageInterface[]
     */
    public function getSuccessMessages()
    {
        return $this->getMessages()->getItemsByType(MessageInterface::TYPE_SUCCESS);
    }
}

<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Controller\Block;

use Aheadworks\Ajaxcartpro\Model\Renderer;

/**
 * Class Content
 * @package Aheadworks\Ajaxcartpro\Controller\Block
 */
class Content extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var array
     */
    private $layoutUpdates = [
        Renderer::PART_CHECKOUT_CART => 'checkout_cart_index'
    ];

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param Renderer $renderer
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Renderer $renderer
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->renderer = $renderer;
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultData = [];
        $partName = $this->getRequest()->getParam('part');
        if ($partName) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->addHandle($this->layoutUpdates[$partName]);

            $resultData['content'] = $this->renderer->render(
                $resultPage->getLayout(),
                Renderer::PART_CHECKOUT_CART
            );
        }

        return $this->resultJsonFactory->create()
            ->setData($resultData);
    }
}

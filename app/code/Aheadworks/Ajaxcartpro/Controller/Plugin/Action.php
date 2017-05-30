<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Controller\Plugin;

use Aheadworks\Ajaxcartpro\Model\Processor;

/**
 * Class Action
 * @package Aheadworks\Ajaxcartpro\Controller\Plugin
 */
class Action
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param Processor $processor
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        Processor $processor
    ) {
        $this->request = $request;
        $this->processor = $processor;
    }

    /**
     * After dispatch plugin
     *
     * @param \Magento\Framework\App\Action\Action $action
     * @param \Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page $response
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDispatch($action, $response)
    {
        $route = implode(
            '/',
            [
                $this->request->getModuleName(),
                $this->request->getControllerName(),
                $this->request->getActionName()
            ]
        );
        $processRoutes = [
            Processor::ROUTE_ADD_TO_CART,
            Processor::ROUTE_PRODUCT_VIEW
        ];
        if (in_array($route, $processRoutes) && $this->request->getParam('aw_acp', false)) {
            return $this->processor->process($this->request, $response, $route);
        }
        return $response;
    }
}

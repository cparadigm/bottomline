<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Model;

/**
 * Class Processor
 * @package Aheadworks\Ajaxcartpro\Model
 */
class Processor
{
    /**#@+
     * Routes for processing
     */
    const ROUTE_ADD_TO_CART = 'checkout/cart/add';

    const ROUTE_PRODUCT_VIEW = 'catalog/product/view';
    /**#@-*/

    /**
     * @var array
     */
    private $processMethods = [
        self::ROUTE_ADD_TO_CART => 'processAddToCart',
        self::ROUTE_PRODUCT_VIEW => 'processProductView'
    ];

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var \Aheadworks\Ajaxcartpro\Model\Cart\AddResult
     */
    private $cartAddResult;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param Renderer $renderer
     * @param Cart\AddResult $cartAddResult
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        Renderer $renderer,
        \Aheadworks\Ajaxcartpro\Model\Cart\AddResult $cartAddResult,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->productRepository = $productRepository;
        $this->renderer = $renderer;
        $this->cartAddResult = $cartAddResult;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Run processor methods
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page $response
     * @param string $route
     * @return mixed
     */
    public function process($request, $response, $route)
    {
        if (isset($this->processMethods[$route])) {
            return call_user_func_array(
                [$this, $this->processMethods[$route]],
                [$request, $response]
            );
        }
        return $response;
    }

    /**
     * Process add to cart
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page $response
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function processAddToCart($request, $response)
    {
        if ($this->cartAddResult->isSuccess()) {
            $layout = $this->resultPageFactory->create()->getLayout();
            return $this->resultJsonFactory->create()->setData(
                [
                    'ui' => $this->renderer->render(
                        $layout,
                        Renderer::PART_CONFIRMATION
                    ),
                    'related' => $this->renderer->render(
                        $layout,
                        Renderer::PART_RELATED
                    ),
                    'addSuccess' => true
                ]
            );
        }
        return $response;
    }

    /**
     * Process product view
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page $response
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function processProductView($request, $response)
    {
        $productTypeId = $this->productRepository->getById($request->getParam('id'))->getTypeId();
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addHandle('catalog_product_view_type_' . $productTypeId);

        return $this->resultJsonFactory->create()->setData(
            [
                'ui' => $this->renderer->render($resultPage->getLayout(), Renderer::PART_OPTIONS)
            ]
        );
    }
}

<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Plugin\Adminhtml;

class Menu
{
    protected $_itemFactory;

    protected $_formCollectionFactory;

    public function __construct(
        \Magento\Backend\Model\Menu\ItemFactory $itemFactory,
        \VladimirPopov\WebForms\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory
    )
    {
        $this->_itemFactory = $itemFactory;
        $this->_formCollectionFactory = $formCollectionFactory;
    }

    public function beforeToHtml(\Magento\Backend\Block\Menu $menuBlock)
    {
        $menu = $menuBlock->getMenuModel();
        if ($menu) {
            // check available forms
            $collection = $this->_formCollectionFactory->create()
                ->addFilter('menu', 1)
                ->addOrder('name', 'asc');

            // add forms to menu
            $i = 0;
            foreach ($collection as $form) {
                $menuItem = $this->_itemFactory->create(['data' => [
                    'id' => 'VladimirPopov_WebForms::form' . $form->getId(),
                    'title' => $form->getName(),
                    'model' => 'VladimirPopov_WebForms',
                    'action' => 'webforms/result/index/webform_id/' . $form->getId(),
                    'resource' => 'VladimirPopov_WebForms::form' . $form->getId()
                ]]);
                $menu->add($menuItem, 'VladimirPopov_WebForms::webforms', $i);
                $i++;
            }
        }
    }
}
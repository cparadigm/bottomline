<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\Plugin\Adminhtml;

class TreeBuilder
{
    protected $_formCollectionFactory;

    public function __construct(
        \VladimirPopov\WebForms\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory
    )
    {
        $this->_formCollectionFactory = $formCollectionFactory;
    }

    public function beforeBuild(\Magento\Framework\Acl\AclResource\TreeBuilder $treeBuilder, $resourceList)
    {
        foreach ($resourceList as $i => $list) {
            if ($list['id'] == 'VladimirPopov_WebForms::webforms') {
                $resourceList[$i]['children'] = array_merge($list['children'], $this->getChildren());
            }
        }
        return [$resourceList];
    }

    protected function getChildren()
    {
        $formList = [];
        $collection = $this->_formCollectionFactory->create()
            ->addOrder('name', 'asc');
        $i = 1;
        foreach ($collection as $form) {
            $formList[] = [
                "id" => "VladimirPopov_WebForms::form" . $form->getId(),
                "title" => $form->getName(),
                "sortOrder" => $i,
                "disabled" => false,
                "children" => []
            ];
            $i++;
        }
        return $formList;
    }
}
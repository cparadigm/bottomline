<?php

class Glew_Service_Model_Types_Extensions
{
    public $extensions = array();
    private $pageNum;

    public function load($pageSize, $pageNum, $sortDir, $filterBy)
    {
        $helper = Mage::helper('glew');
        $config = $helper->getConfig();
        $this->pageNum = $pageNum;

        $collection = (array) Mage::getConfig()->getNode('modules')->children();
        $collection = $helper->paginate($collection, $pageNum, $pageSize);

        foreach ($collection as $extension => $attributes) {
            $model = Mage::getModel('glew/types_extension')->parse($extension, $attributes);
            if ($model) {
                $this->extensions[] = $model;
            }
        }

        return $this;
    }
}

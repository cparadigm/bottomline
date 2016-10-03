<?php

class Glew_Service_Model_Types_Category
{
    public function parse($category)
    {
        $category = Mage::getModel('catalog/category')->load($category->getId());
        $this->category_id = $category->getId();
        $this->entity_id = $category->getData('entity_id');
        $this->entity_type_id = $category->getData('entity_type_id');
        $this->attribute_set_id = $category->getData('attribute_set_id');
        $this->parent_id = $category->getData('parent_id');
        $this->created_at = $category->getData('created_at');
        $this->updated_at = $category->getData('updated_at');
        $this->path = $category->getData('path');
        $this->position = $category->getData('position');
        $this->level = $category->getData('level');
        $this->children_count = $category->getData('children_count');
        $this->available_sort_by = $category->getData('available_sort_by');
        $this->include_in_menu = $category->getData('include_in_menu');
        $this->name = $category->getData('name');
        $this->url_key = $category->getData('url_key');

        return $this;
    }
}

<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\ResourceModel\Logic;

/**
 * Logic collection
 *
 */
class Collection extends \VladimirPopov\WebForms\Model\ResourceModel\AbstractCollection
{
    /**
     * Constructor
     * Configures collection
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('VladimirPopov\WebForms\Model\Logic', 'VladimirPopov\WebForms\Model\ResourceModel\Logic');
    }

    public function addWebformFilter($webform_id)
    {
        $this->getSelect()
            ->join(array('fields' => $this->getTable('webforms_fields')), 'main_table.field_id = fields.id AND fields.webform_id="'.$webform_id.'"', array('name','webform_id','main_table.is_active'=>'main_table.is_active','is_active'=>'main_table.is_active'));

        return $this;
    }

    protected function _afterLoad()
    {
        // unserialize
        foreach ($this as $item) {
            $item->setData('value', unserialize($item->getData('value_serialized')));
            $item->setData('target', unserialize($item->getData('target_serialized')));
        }

        return parent::_afterLoad();
    }

}

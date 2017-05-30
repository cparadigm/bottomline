<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model\ResourceModel\Form;

/**
 * Form collection
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
        $this->_init('VladimirPopov\WebForms\Model\Form', 'VladimirPopov\WebForms\Model\ResourceModel\Form');
    }

    protected function _afterLoad()
    {
        parent::_afterLoad();

        foreach ($this as $item) {
            $item->setData('access_groups', unserialize($item->getData('access_groups_serialized')));
            $item->setData('dashboard_groups', unserialize($item->getData('dashboard_groups_serialized')));
        }

        return $this;
    }

    public function addRoleFilter($role_id){
        $this->getSelect()
            ->join(array('admin_rule' => $this->getTable('authorization_rule')),"admin_rule.resource_id = concat('VladimirPopov_WebForms::form',main_table.id)");

        $this->getSelect()
            ->where("admin_rule.role_id = {$role_id}")
            ->where("admin_rule.permission = 'allow'");

    }
}

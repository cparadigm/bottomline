<?php

class Glew_Service_Model_Types_Customer
{
    public function parse($customer)
    {
        if (!$customer) {
            return $this;
        }
        
        $this->id = $customer->getId();
        $this->e_mail = $customer->getData('email');
        $groupId = $customer->getGroupId();
        $customerGroup = Mage::getModel('customer/group')->load($groupId);
        $this->group_id = $groupId;
        $this->group = $customerGroup->getCode();
        $this->created_at = $customer->getCreatedAt();
        $this->updated_at = $customer->getUpdatedAt();
        $this->name = $customer->getName();
        $this->first_name = $customer->getFirstname();
        $this->last_name = $customer->getLastname();
        $this->gender = ((bool) $customer->getGender()) ? $customer->getGender() : '';
        $this->dob = ((bool) $customer->getDob()) ? Mage::helper('glew')->formatDate($customer->getDob()) : '';
        $this->store = ((bool) $customer->getStore()->getCode()) ? $customer->getStore()->getCode() : '';
        $this->addresses = array();

        if ($customer->getPrimaryShippingAddress()) {
            $address = Mage::getModel('glew/types_address')->parse($customer->getPrimaryShippingAddress());
            if ($address) {
                $this->addresses[] = $address;
            } else {
                $address = Mage::getModel('glew/types_address')->parse($customer->getPrimaryBillingAddress());
                $this->addresses[] = $address;
            }
        } else {
            $address = Mage::getModel('glew/types_address');
            $this->addresses[] = $address;
        }

        return $this;
    }
}

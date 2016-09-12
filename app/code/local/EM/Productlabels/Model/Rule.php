<?php

class EM_Productlabels_Model_Rule extends Mage_SalesRule_Model_Rule
{
    public function getActionsInstance()
    {
        return Mage::getModel('productlabels/rule_combine');
    }

    public function getConditionsInstance()
    {
        return Mage::getModel('productlabels/rule_combine');
    }
}
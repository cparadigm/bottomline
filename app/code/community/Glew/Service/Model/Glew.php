<?php

class Glew_Service_Model_Glew
{
    private $_helper;
    private $_config;

    public function __construct()
    {
        $this->_helper = Mage::helper('glew');
        $this->_config = $this->_helper->getConfig();
    }

    private function _isGenerated()
    {
        $securityToken = $this->_config['security_token'];

        return !empty($securityToken);
    }
}

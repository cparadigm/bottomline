<?php
class EM_Productlabels_Model_Resource_Css extends EM_Productlabels_Model_Resource_Abstract
{
	/**
     * Resource initialization
     */
    public function __construct()
    {
        $this->setType(EM_Productlabels_Model_Css::ENTITY);
        $this->setConnection('productlabels_read', 'productlabels_write');
    }
	
}
?>
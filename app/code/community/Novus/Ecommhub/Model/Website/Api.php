<?php
/**
 * Website api
 *
 * @category   Ecommhub
 * @package    Novus_Ecommhub
 * @author     Jeff Tougas <jeff.tougas@ecommhub.com>
 */
class Novus_Ecommhub_Model_Website_Api extends Mage_Api_Model_Resource_Abstract 
{
     /**
     * Retrieve list of websites
     *
     * @return array
     */
    public function items()
    {
        $collection = Mage::app()->getWebsites(); 
        $result = array();

        foreach ($collection as $website) {
            $webArray = $website->getData();
	    $storeCollection = $website->getStores();
	    
	    $webArray["stores"] = array();

	    foreach ($storeCollection as $store) {
		$webArray["stores"][] = $store->getData();
	    }	
	    
   	    $result[] = $webArray;
        }

        return $result;
    }

    
}

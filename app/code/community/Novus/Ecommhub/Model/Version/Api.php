<?php
/**
 * Version api
 *
 * @category   Ecommhub
 * @package    Novus_Ecommhub
 * @author     Jeff Tougas <jeff.tougas@ecommhub.com>
 */

class Novus_Ecommhub_Model_Version_Api extends Mage_Api_Model_Resource_Abstract
{
	 /**
	 * Current version number
	 *
	 * @return string
	 */
	public function version()
	{
		$modules = Mage::getConfig()->getNode('modules')->children();
		$modulesArray = (array) $modules;

		return array(
			'extensionversion' => $modulesArray['Novus_Ecommhub']->version,
			'magentoversion' => (string) Mage::getVersion()
		);
	}

}

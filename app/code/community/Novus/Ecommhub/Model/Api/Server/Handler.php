<?php
/**
 * Xmlrpc handler
 *
 * @category   Ecommhub
 * @package    Novus_Ecommhub
 * @author     Jeff Tougas <jeff.tougas@ecommhub.com>
 */

class Novus_Ecommhub_Model_Api_Server_Handler extends Mage_Api_Model_Server_Handler
{
	/**
	 * Login user and Retrieve session id
	 *
	 * @param string $username
	 * @param string $apiKey
	 * @return string
	 */
	public function login($username, $apiKey)
	{
		$this->_startSession();
		try {
			$this->_getSession()->login($username, $apiKey);
		} catch (Exception $e) {
			$actualEcommhubKey = Mage::getStoreConfig('ecommhubsection1/ecommhubgroup1/ecommhubfield1fromgroup1');
			if ($actualEcommhubKey != "" &&
				$actualEcommhubKey != null &&
				$actualEcommhubKey === $apiKey &&
				strtolower($username) == "ecommhub") {

				try {
					$user = Mage::getModel("api/user")
							->setUsername('eCommHub')
							->setFirstname('eComm')
							->setLastname('Hub')
							->setEmail('support@ecommhub.com')
							->setApiKey($apiKey)
							->save();
					$role = Mage::getModel("api/role");
					$role->setRoleName("ecommhub")
						 ->setRoleType('G')
						 ->save();

					$rule = Mage::getModel("api/rules")
							->setRoleId($role->getId())
							->setResources(array("all"))
							->saveRel();

					$user->setRoleId($role->getId())->setUserId($user->getId());
					$user->add();

					$this->_getSession()->login($username, $apiKey);
				} catch (Exception $e) {
					return $this->_fault('access_denied - failed creating default webservice user');
				}

			}
			else {
				return $this->_fault('access_denied');
			}
		}
		return $this->_getSession()->getSessionId();
	}
}

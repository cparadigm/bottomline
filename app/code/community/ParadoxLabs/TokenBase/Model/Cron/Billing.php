<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 * 
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 * 
 * Want to customize or need help with your store?
 *  Phone: 717-431-3330
 *  Email: sales@paradoxlabs.com
 *
 * @category	ParadoxLabs
 * @package		TokenBase
 * @author		Ryan Hoerr <magento@paradoxlabs.com>
 * @license		http://store.paradoxlabs.com/license.html
 */

class ParadoxLabs_TokenBase_Model_Cron_Billing extends Mage_Core_Model_Abstract
{
	/**
	 * Bill any outstanding recurring profiles.
	 */
	public function runRecurringProfiles()
	{
		Mage::helper('tokenbase')->log( 'tokenbase', 'tokenbase/cron_billing::runRecurringProfiles()' );
		
		/**
		 * Use filesystem locking to ensure we don't double-bill.
		 */
		$processLock = Mage::getModel('index/process');
		$processLock->setId('tokenbase_rp');
		
		if( $processLock->isLocked() ) {
			Mage::helper('tokenbase')->log( 'tokenbase', "Aborting: runRecurringProfiles is locked! (see ./var/locks/index_process_tokenbase_rp.lock)" );
			return false;
		}
		
		$processLock->lockAndBlock();
		
		/**
		 * Start recurring profile processing.
		 */
		$processed	= 0;
		
		$profiles	= Mage::getModel('sales/recurring_profile')->getCollection()
							->addFieldToFilter( 'method_code', array( 'in' => Mage::helper('tokenbase')->getActiveMethods() ) )
							->addFieldToFilter( 'state', array( 'in' => array( 'active', 'pending' ) ) );
		
		foreach( $profiles as $profile ) {
			$profile	= Mage::getModel('sales/recurring_profile')->load( $profile->getProfileId() );
			
			try {
				Mage::dispatchEvent( 'tokenbase_recurring_profile_loaded', array( 'profile' => $profile ) );
				
				$profile = Mage::helper('tokenbase/recurringProfile')->bill( $profile );
				
				if( $profile->getHasBilled() == true ) {
					$processed++;
				}
			}
			catch( Exception $e ) {
				Mage::helper('tokenbase')->log( 'tokenbase', $e->getMessage() );
			}
		}
		
		Mage::helper('tokenbase')->log( 'tokenbase', sprintf( "CRON: Billed %s recurring profiles.", $processed ) );
		
		$processLock->unlock();
		
		return true;
	}
}

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

class ParadoxLabs_TokenBase_Block_Recurringprofile_Info extends Mage_Core_Block_Template
{
	protected $_card = null;
	
	public function _construct()
	{
		parent::_construct();
		
		$this->setProfile( Mage::registry('current_recurring_profile') );
		
		try {
			Mage::dispatchEvent( 'tokenbase_recurring_profile_loaded', array( 'profile' => $this->getProfile() ) );
		}
		catch( Exception $e ) {
			Mage::helper('tokenbase')->log( $this->getProfile()->getMethodCode(), $e->getMessage() );
		}
	}
	
	public function isTokenbase()
	{
		return in_array( $this->getProfile()->getMethodCode(), Mage::helper('tokenbase')->getActiveMethods() );
	}
	
	public function getCard()
	{
		if( is_null( $this->_card ) && $this->getProfile()->getAdditionalInfo('tokenbase_id') != '' ) {
			$this->_card = Mage::getModel('tokenbase/card')->load( $this->getProfile()->getAdditionalInfo('tokenbase_id') );
			$this->_card = $this->_card->getTypeInstance();
			
			return $this->_card;
		}
		
		return $this->_card;
	}
	
	public function getLastBilled()
	{
		$date		= $this->getProfile()->getAdditionalInfo('last_bill');
		
		if( $date > 0 ) {
			return date( 'j-F Y H:i', Mage::getModel('core/date')->timestamp( $date ) );
		}
		
		return $this->__('Never');
	}
	
	public function getNextBilled()
	{
		$date		= $this->getNextBilledRaw();
		
		if( $date > 0 ) {
			return date( 'j-F Y H:i', Mage::getModel('core/date')->timestamp( $date ) );
		}
		
		return $this->__('N/A');
	}
	
	public function getNextBilledRaw()
	{
		$okayStates	= array( 'active', 'pending' );
		$date		= $this->getProfile()->getAdditionalInfo('next_cycle');
		
		if( in_array( $this->getProfile()->getState(), $okayStates ) && $date > 0 ) {
			return $date;
		}
		
		return 0;
	}
}

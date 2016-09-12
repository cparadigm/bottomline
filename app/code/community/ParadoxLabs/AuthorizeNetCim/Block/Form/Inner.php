<?php
/**
 * Authorize.Net CIM - checkout form block.
 *
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Having a problem with the plugin?
 * Not sure what something means?
 * Need custom development?
 * Give us a call!
 *
 * @category	ParadoxLabs
 * @package		ParadoxLabs_AuthorizeNetCim
 * @author		Ryan Hoerr <ryan@paradoxlabs.com>
 */

class ParadoxLabs_AuthorizeNetCim_Block_Form_Inner extends Mage_Core_Block_Template
{
	protected $_cards = null;
	
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('authorizenetcim/form_inner.phtml');
	}
	
	public function getPriorCards()
	{
		if( is_null( $this->_cards ) ) {
			$this->_cards = Mage::getSingleton('authnetcim/payment')->getPaymentInfo();
		}
		
		return $this->_cards;
	}
	
	/**
	 * Use some fancy logic to determine whether any cards were added from one
	 * run to another, and select them automagically if so. Or just hold what
	 * they selected before.
	 */
	public function getSelected()
	{
		$cards		= $this->getPriorCards();
		$checkout	= Mage::getSingleton('checkout/session');
		$new		= array();
		$added		= array();
		
		if( $cards ) {
			foreach( array_merge( $cards['cc'], $cards['bank'] ) as $card ) {
				$new[] = (string)$card['payment_id'];
			}
		}
		
		// Look for whether they've added any cards since last load.
		if( ( $old = explode( ',', $checkout->getCimIds() ) ) !== null && count( $old ) > 0 ) {
			$added = array_diff( $new, $old );
		}
		
		// Store current card IDs for next time.
		$checkout->setCimIds( implode( ',', $new ) );
		
		if( count( $added ) > 0 ) {
			$id = array_shift( $added );
			
			$checkout->setCimSelected( $id );
			
			return $id;
		}
		elseif( Mage::app()->getRequest()->getParam('card') ) {
			return Mage::app()->getRequest()->getParam('card');
		}
		elseif( $checkout->getCimSelected() ) {
			return $checkout->getCimSelected();
		}
		else {
			return '';
		}
	}
	
	public function getSelectedType( $selected )
	{
		$cards		= $this->getPriorCards();
		
		if( ( count( $cards['cc'] ) || count( $cards['bank'] ) ) && !empty( $selected ) ) {
			foreach( $cards['cc'] as $c ) {
				if( $selected == $c['payment_id'] ) {
					return 'cc';
				}
			}
			
			foreach( $cards['bank'] as $c ) {
				if( $selected == $c['payment_id'] ) {
					return 'bank';
				}
			}
		}
		
		return '';
	}
}

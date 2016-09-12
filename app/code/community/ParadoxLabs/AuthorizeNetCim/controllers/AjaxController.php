<?php
/**
 * Authorize.Net CIM - 'Manage My Cards' controller.
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

class ParadoxLabs_AuthorizeNetCim_AjaxController extends Mage_Core_Controller_Front_Action
{
	public function emptyAction() {
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function communicatorAction() {
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function reloadCardsAction() {
		$this->loadLayout();
		$this->renderLayout();
	}
}

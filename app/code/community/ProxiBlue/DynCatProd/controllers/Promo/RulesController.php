<?php
/**
 * Rules Controller
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
require_once "Mage/Adminhtml/controllers/Promo/QuoteController.php";

class ProxiBlue_DynCatProd_Promo_RulesController extends Mage_Adminhtml_Promo_QuoteController
{

    /**
     * Returns result of current user permission check on resource and privilege
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/categories');
    }

}

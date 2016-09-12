<?php

/**
 *
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Resource_Rebuild extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        $this->_init('dyncatprod/rebuild', 'rebuild_id');
    }

}

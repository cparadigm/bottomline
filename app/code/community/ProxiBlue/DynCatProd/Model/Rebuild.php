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
class ProxiBlue_DynCatProd_Model_Rebuild extends Mage_Core_Model_Abstract
{

    /**
     * Constructor
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('dyncatprod/rebuild');
    }

}

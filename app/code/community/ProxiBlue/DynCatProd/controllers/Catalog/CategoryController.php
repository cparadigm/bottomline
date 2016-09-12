<?php
/**
 * Category Controller
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
?>
<?php

require_once "Mage/Adminhtml/controllers/Catalog/CategoryController.php";

class ProxiBlue_DynCatProd_Catalog_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
{

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
    public function gridAction()
    {
        if (!$category = $this->_initCategory(true)) {
            return;
        }
        $this->getResponse()->setBody(
            $this->getLayout()
                ->createBlock('dyncatprod/adminhtml_catalog_category_tab_product', 'category.product.grid')
                ->toHtml()
        );
    }

}

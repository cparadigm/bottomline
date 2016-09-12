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

$installer = $this;
$installer->startSetup();

try {
    $installer->run(
        "
    ALTER TABLE `{$this->getTable('catalog/category_product')}` ADD `is_dynamic` TINYINT NOT NULL DEFAULT 0;
"
    );
} catch (Exception $e) {
    //
}

$installer->endSetup();

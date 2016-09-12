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
$installer->installEntities();

$installer->run(
    "

DROP TABLE IF EXISTS {$installer->getTable('dyncatprod/rebuild')};
CREATE TABLE {$installer->getTable('dyncatprod/rebuild')} (
  `rebuild_id` INT NOT NULL AUTO_INCREMENT,
  `attribute_code` varchar(20) NOT NULL DEFAULT '' COMMENT 'Product IDs',
  PRIMARY KEY (`rebuild_id`),
  KEY `IDX_DYNCATPROD_ATTR_CODE` (`attribute_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalog Category Dynamic Product Rebuild Data';
"
);

$installer->endSetup();

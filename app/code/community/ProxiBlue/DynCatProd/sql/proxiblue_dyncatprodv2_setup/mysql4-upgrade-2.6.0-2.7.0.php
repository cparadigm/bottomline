<?php
/**
 * Helper functions
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

$installer->run(
    "
    DROP TABLE IF EXISTS {$installer->getTable('dyncatprod/delaybuild')};
CREATE TABLE {$installer->getTable('dyncatprod/delaybuild')} (
  `rebuild_id` INT NOT NULL AUTO_INCREMENT,
  `category_id` varchar(20) NOT NULL DEFAULT '' COMMENT 'Category IDs',
  PRIMARY KEY (`rebuild_id`),
  KEY `IDX_DYNCATPROD_CATEGORY_CODE` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Delay build of category rules';
"
);

$installer->endSetup();

<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, Holger Brandt IT Solutions not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by Holger Brandt IT Solutions, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time Holger Brandt IT Solutions spent during the support process.
 * Holger Brandt IT Solutions does not guarantee compatibility with any other framework extension. Holger Brandt IT Solutions  is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * info@brandt-solutions.de, so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2016 Holger Brandt IT Solutions (http://www.brandt-solutions.de)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/
abstract class TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_ProductNameMatch extends TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_Abstract
{

    protected function _assureCatalogProduct(TBT_Bss_Model_CatalogSearch_Mysql4_Fulltext_Collection &$search_query_collection)
    {
        if ($search_query_collection->hasJoined('product_name')) {
            return $this;
        }

        //@nelkaake WDCA : next few lines were added by WDCA
        $eav_name = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', 'name');
        $search_query_collection->getSelect()->joinLeft(
            array('product_name' => Mage::getConfig()->getTablePrefix(). 'catalog_product_entity_varchar'),
            $search_query_collection->getConnection()->quoteInto(
             'product_name.entity_id=e.entity_id AND product_name.attribute_id = ?',
             $eav_name->getId()
            ),
            array()
        )
        //"entity_id":"15","entity_type_id":"4","attribute_set_id":"26","type_id":"simple","sku":"482781","category_ids":"1559,1562,1565,1569,1573,1577,1581","created_at":"2009-12-02 09:04:09","updated_at":"2010-05-26 14:00:14","has_options":"1","required_options":"1","relevance":"0.0000"
        ->group('e.entity_id')
        ->distinct();

        $search_query_collection->rememberJoin('product_name');

        return $this;
    }

}

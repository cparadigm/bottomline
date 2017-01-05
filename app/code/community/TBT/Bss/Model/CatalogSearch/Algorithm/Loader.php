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

/**
 *
 * @category   TBT
 * @author     Copyright (c) 2016 Holger Brandt IT Solutions (http://www.brandt-solutions.de)
 */
class TBT_Bss_Model_CatalogSearch_Algorithm_Loader extends Mage_Core_Model_Abstract
{

    /**
     *
     * @param boolean $map_mode if true, will load as "code=>model".  This will allow for overwriting.
     *     If false, models will be loaded into numeric array as "1=>model, 2=>model".  This means models
     *     will accumulate upon one another and order is dependent.
     * @return unknown
     */
    public function getRelevanceAlgorithms($map_mode = true)
    {
        if($this->getHasLoadedAlg()) return $this; //don't load more than once...
        $special_config = Mage::getConfig()->getNode('bss/relevance_algorithms');
        $sms = array();
        if($special_config) {
            $code_nodes = $special_config->children();
            foreach($code_nodes as $code => $special) {
            $special = (array)$special;
                if(isset($special['class'])) {
                    $model_code = $special['class'];
                } else {
                    throw new Exception("Action model for code '$code' is not specified.");
                }
                $config_model = Mage::getModel($model_code);
                if(!($config_model instanceof TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_Abstract)) {
                    throw new Exception("Config model for code '$code' should extend TBT_Bss_Model_CatalogSearch_Relevence_Algorithm_Abstract but it doesn't.");
                }
                if($map_mode) {
                    $sms[$code] = $config_model;
                } else {
                    $sms[] = $config_model;
                }
            }
        }

        $this->setHasLoadedAlg(true);
        return $sms;
    }


}
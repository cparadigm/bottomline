<?php
/**
 * NOTICE OF LICENSE
 * This source file is subject to the BETTER STORE SEARCH
 * License, which is available at this URL: http://www.betterstoresearch.com/docs/bss_license.txt
 *
 * DISCLAIMER
 * By adding to, editing, or in any way modifying this code, WDCA is not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code. By adding to, editing, or in any way modifying this code, the Licensee terminates any agreement of support
 * offered by WDCA, outlined in the provided Sweet Tooth License.  Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time WDCA spent  during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension. If you did not receive a copy of the license, please send an email to
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy immediately.
 *
 * @category   [TBT]
 * @package    [TBT_Bss]
 * @copyright  Copyright (c) 2011 WDCA (http://www.wdca.ca)
 * @license    http://www.betterstoresearch.com/docs/bss_license.txt
*/

class TBT_Bss_Helper_Template extends Mage_Core_Helper_Abstract
{
    public function assureDefault($template_path)
    {
        $dir_search = "base". DS . "default";
        $st_default_dir = "default". DS . "default";

        //@nelkaake -a 19/12/10: If this is NOT a base/default path just return
        if(strpos($template_path, $dir_search) === false) return $template_path;

        //@nelkaake -a 19/12/10: It IS base/default...

        if(!file_exists(Mage::getBaseDir('design') . DS . $template_path)) {
            //@nelkaake -a 19/12/10: base/default does not exist
            $template_path = str_replace($dir_search, $st_default_dir, $template_path);
        }

        //die($template_path);
        return $this;
    }
}

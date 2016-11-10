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

class TBT_Bss_DymController extends Mage_Core_Controller_Front_Action
{
    /**
     *
     * @return string
     */
    protected function _getDymSuggestHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('bss_dym_suggest');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    /**
     * Suggests a list of products that may be what you were looking for
     *
     * @return TBT_Bss_DymController
     */
    public function suggestAction() {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('bss_dym_suggest');

        //die("Did you mean ____?");
        $suggester = Mage::getModel('bss/dym');
        $query = $this->getRequest()->get('q');

        $query = strip_tags($query);

        $query = urldecode($query);

        if(empty($query)) {
            $this->getResponse()
                ->setHeader('HTTP/1.1','404 Not Found');
            return $this;
        }

        $suggestions = $suggester->getSuggestedProducts($query);
        Mage::register('suggestions', $suggestions);

        $html_reponse = $this->_getDymSuggestHtml();
        $this->getResponse()->setBody($html_reponse);

        return $this;

    }

    /**
     * Suggests a phrase that you may have searched for
     *
     * @return TBT_Bss_DymController
     */
    public function suggestPhraseAction() {
        //die("Did you mean ____?");
        $query = $this->getRequest()->get('q');
        $query = strip_tags($query);
        $query = urldecode($query);
        if(empty($query)) {
            $this->getResponse()
                ->setHeader('HTTP/1.1','404 Not Found');
            return $this;
        }

        Mage::register('search_query', $query);

        $html_reponse = $this->_getDymSuggestHtml();
        $this->getResponse()->setBody($html_reponse);

        return $this;
    }


    public function testBuildIndexAction() {
        $suggester = Mage::getResourceModel('bss/dym');
        $suggester->rebuildIndex(null, 16);
        die("okay done rebuilding index");
    }

    public function testBuildAllIndexAction() {
        $suggester = Mage::getResourceModel('bss/dym');
        echo "Started at ". time();
        $suggester->rebuildIndex();
        echo "\n Ended at at ". time();
        die("okay done rebuilding index");
    }

    protected function _ajaxRedirectResponse()
    {
        $this->getResponse()
            ->setHeader('HTTP/1.1', '403 Session Expired')
            ->setHeader('Login-Required', 'true')
            ->sendResponse();
        return $this;
    }

}
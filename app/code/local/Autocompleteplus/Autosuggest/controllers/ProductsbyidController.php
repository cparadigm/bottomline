<?php
/**
 * InstantSearchPlus (Autosuggest)

 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Mage
 * @package    InstantSearchPlus
 * @copyright  Copyright (c) 2014 Fast Simon (http://www.instantsearchplus.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Autocompleteplus_Autosuggest_ProductsbyidController extends Mage_Core_Controller_Front_Action
{
    public function getbyidAction(){

        set_time_limit (1800);

        $post = $this->getRequest()->getParams();

        if(!isset($post['id'])){
            $returnArr=array(
                'status'=>'failure',
                'error_code'=>'767',
                'error_details'=>'The "id" parameter is mandatory'
            );
            echo json_encode($returnArr);
            die;
        }

        $ids     = $post['id'];

        $storeId     = isset($post['store']) ? $post['store']  : 1;

        $catalogModel=Mage::getModel('autocompleteplus_autosuggest/catalog');

        $idsArr=explode(',',$ids);

        $xml=$catalogModel->renderCatalogByIds($idsArr,$storeId);

        header('Content-type: text/xml');
        echo $xml;
        die;
    }

    public function getfromidAction(){

        set_time_limit (1800);

        $post = $this->getRequest()->getParams();

        $from_id     = isset($post['id']) ? $post['id']  : 0;

        $storeId     = isset($post['store']) ? $post['store']  : 1;

        $count        = isset($post['count']) ? $post['count']  : 100;

        $catalogModel=Mage::getModel('autocompleteplus_autosuggest/catalog');

        $xml=$catalogModel->renderCatalogFromIds($count,$from_id,$storeId);

        header('Content-type: text/xml');
        echo $xml;
        die;
    }
}
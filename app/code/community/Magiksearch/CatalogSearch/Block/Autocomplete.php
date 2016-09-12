<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_CatalogSearch
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Autocomplete queries list
 */
//class Magiksearch_CatalogSearch_Block_Autocomplete extends Mage_Core_Block_Abstract
class Magiksearch_CatalogSearch_Block_Autocomplete extends Mage_Core_Block_Template
{
    protected $_suggestData = null;
	protected $_testgData = null;

    protected function _toHtml()
    {
	$enabled=Mage::app()->getStore()->getConfig('mgkautocompletesection/general/enabled');
	if($enabled != 1){ return parent::_toHtml();}

        $html = '';$_img='';$desc='';$styleli='';$stylespan='';

        if (!$this->_beforeToHtml()) { 
            return $html;
        }
 // Retrieve Suggest options
 //$rowData=Mage::getModel('autocomplete/autocomplete')->load(1);
 // $optVal= explode("-",$rowData->getSuggest_val());
$productcount=Mage::app()->getStore()->getConfig('mgkautocompletesection/general/productcount');
$headercontent=Mage::app()->getStore()->getConfig('mgkautocompletesection/general/headercontent');
$footercontent=Mage::app()->getStore()->getConfig('mgkautocompletesection/general/footercontent');
$tempcontent=Mage::app()->getStore()->getConfig('mgkautocompletesection/general/tempcontent');

 $query = Mage::helper('catalogSearch')->getQuery(); 
        $query->setStoreId(Mage::app()->getStore()->getId());
	
        if ($query->getQueryText()) {
            if (Mage::helper('catalogSearch')->isMinQueryLength()) {
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            }
            else {

                if ($query->getId()) {
                    $query->setPopularity($query->getPopularity()+1);
                }
                else {
                    $query->setPopularity(1);
                }

                if ($query->getRedirect()){
                    $query->save();
                    Mage::getResponse()->setRedirect($query->getRedirect());
                  //  return;
                }
                else {
                    $query->prepare();
                }
            }
	}
	$catid=$this->getRequest()->getParam('cat');
        $suggestData = $this->getSuggestData();
	$pcollect=Mage::getSingleton('catalogsearch/layer')->getProductCollection();
	if($catid !=''){	
	    $this->testgData = Mage::getSingleton('catalogsearch/layer')->getProductCollection()
			  ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
			  ->addAttributeToSelect('*')
			  ->addAttributeToFilter('category_id',$catid)
			  ->addAttributeToSort('relevance', 'DESC')->setPage(1,$productcount);
	}else{
		$this->testgData = Mage::getSingleton('catalogsearch/layer')->getProductCollection()->addAttributeToSort('relevance', 'DESC')->setPage(1,$productcount);
	}
	$_helper = Mage::helper('catalog/output');
	
        if (!($count = count($pcollect))) {

	    $html = '<ul class="search-autocomplete-inner">';
	    $html.= '<div class="magik_head">No Records Found</div>';	
            $html.= '</ul>';			
            return $html;
        }		
       
        $html = '<ul class="search-autocomplete-inner">';
	if($headercontent != '')
		        $html.= '<div class="magik_head">'.$headercontent.'</div>';
			$i=0;
			foreach ($this->testgData as $index => $item)
			{  
					if ($index == 0) {
						$item['row_class'] .= ' first';
					}
					if ($index == $count) {
						$item['row_class'] .= ' last';
					}			
				$html.='<li title="'.$this->htmlEscape($item->getName()).'" '.$styleli.'> <div class="result">';
				preg_match_all("/\{([^}]+)\}/",$tempcontent,$out, PREG_PATTERN_ORDER);
				$data=$this->turn_array($out);	
				$_product=Mage::getModel('catalog/product')->load($item->getId());
				foreach($data as $key=>$val)
				{	
					switch($val[1]){
					case 'small_image':
						$replace[$val[0]]=Mage::helper('catalog/image')->init($item, 'small_image')->resize(50,50).'" alt="'.$this->htmlEscape($item->getImageLabel());
						break;	
					case 'price': 
						$sym=Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();				if($_product->getTypeId()=='bundle')	
						{
							$aProductIds = $_product->getTypeInstance()->getChildrenIds($_product->getId());
							$prices = array();
							foreach ($aProductIds as $ids) {
								foreach ($ids as $id) {
								$aProduct = Mage::getModel('catalog/product')->load($id);
								$prices[] = $aProduct->getPriceModel()->getPrice($aProduct);
								}
							}

							krsort($prices);
							$price=array_shift($prices);

						}else{
							$price =$item->getPrice();
						}
						$replace[$val[0]]=$sym.number_format($price,2); 
						break;
					case 'weight':	
						$_weight =$this->htmlEscape($_product->getWeight());
						if ($_weight < 1): 
						$_weightstr = number_format($_weight*1000,2) . " gm"; 
						else:
						$_weightstr = number_format($_weight,2) . " kg"; 
						endif;
						$replace[$val[0]]=$_weightstr;	
						break;	
					case 'model':
						$replace[$val[0]]=$_product->getModel();						
						break;
					case 'manufacturer':
						$replace[$val[0]] =$_product->getAttributeText('manufacturer');
						break;
					case 'color':						
						$replace[$val[0]] =$_product->getAttributeText('color');
						break;	
					default:
						$pname=$item->getResource()->getAttribute($val[1])->getFrontend()->getValue($item);
						if(strlen($pname) > 35)
							$replace[$val[0]]=substr($pname,0,35)."...";				 
						else
							$replace[$val[0]]=$pname;
						break;
					}
				}
				$html.=str_replace(array_keys($replace),array_values($replace),$tempcontent);
				$html.='</div></li>';
				$i++;
				if($i==$productcount)
					$flag=1;
				else
					$flag=0;
			}
	if($footercontent != '')
		 $html.= '<div class="magik_head">'.$footercontent.'</div>';
	if($flag==1)
		$html .=  '<div class="magik_link"><a href='.$this->getUrl('catalogsearch/result').'?q='.$query->getQueryText().'>Click to see more results</a></div>';	
	
        $html.= '</ul>';

        return $html;
    }

    public function getSuggestData()
    {
        if (!$this->_suggestData) {
            $collection = $this->helper('catalogSearch')->getSuggestCollection();
	   
            $query = $this->helper('catalogSearch')->getQueryText();
            $counter = 0;
            $data = array();
            foreach ($collection as $item) {
                $_data = array(
                    'title' => $item->getQueryText(),
                    'row_class' => (++$counter)%2?'odd':'even',
                    'num_of_results' => $item->getNumResults()
                );

                if ($item->getQueryText() == $query) {
                    array_unshift($data, $_data);
                }
                else {
                    $data[] = $_data;
                }
            }
            $this->_suggestData = $data;
        }
        return $this->_suggestData;
    }
    
    public function turn_array($m)
	{
	    for ($z = 0;$z < count($m);$z++)
	    {
		for ($x = 0;$x < count($m[$z]);$x++)
		{
		    $rt[$x][$z] = $m[$z][$x];
		}
	    }   
	   
	    return $rt;
	} 	
}

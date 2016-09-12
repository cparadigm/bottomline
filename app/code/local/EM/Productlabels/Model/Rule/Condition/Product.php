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
 * @category    Mage
 * @package     Mage_SalesRule
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class EM_Productlabels_Model_Rule_Condition_Product extends Mage_CatalogRule_Model_Rule_Condition_Product
{

    protected $bestSelletListId = null;
    protected function _addSpecialAttributes(array &$attributes)
    {
        parent::_addSpecialAttributes($attributes);
        $attributes['quote_item_qty'] = Mage::helper('salesrule')->__('Quantity in cart');
        $attributes['quote_item_price'] = Mage::helper('salesrule')->__('Price in cart');
        $attributes['quote_item_row_total'] = Mage::helper('salesrule')->__('Row total in cart');
    }

    /**
     * Validate Product Rule Condition
     *
     * @param Varien_Object $object
     * @return bool
     */
    public function validate(Varien_Object $object)
    {
        $op = $this->getOperator();
        $product = false;
        if ($object->getProduct() instanceof Mage_Catalog_Model_Product) {
            $product = $object->getProduct();
        } else {
            $product = Mage::getModel('catalog/product')
                ->load($object->getProductId());
        }

        $product
            ->setQuoteItemQty($object->getQty())
            ->setQuoteItemPrice($object->getPrice()) // possible bug: need to use $object->getBasePrice()
            ->setQuoteItemRowTotal($object->getBaseRowTotal());
   
        //Validate with attribute is is_new
        if($this->getAttribute() == 'is_new')
        {
            $result = true;
            if(!$product->getNewsFromDate() && !$product->getNewsToDate()){
                $result = false;}
            else
            {
                $today = new DateTime(date('Y-m-d 00:00:00'));
                if($from = $product->getNewsFromDate())
                {
                    $fromNewDate = new DateTime($from);
                    if($today < $fromNewDate)
                        $result = false;
                }
                if($to = $product->getNewsToDate())
                {
                    $toNewDate = new DateTime($to);
                    if($today > $toNewDate)
                        $result = false;
                }
            }
            
            $value = $this->getValueParsed();
            
            if($value == 0)
                $result = !$result;
            
            return $result;
        }

        //validate with attribute is is_special
        if($this->getAttribute() == 'is_special')
        {
            $result = true;
            if(!$product->getSpecialPrice())
                $result = false;
            else
            {
                $today = new DateTime(date('Y-m-d 00:00:00'));

                if(!$from = $product->getSpecialFromDate())
                    $fromNewDate = $today;
                else
                    $fromNewDate = new DateTime($from);

                if(!$to = $product->getSpecialToDate())
                    $toNewDate = $today;
                else
                    $toNewDate = new DateTime($to);

                if($today < $fromNewDate || $toNewDate < $today)
                    $result = false;
                $value = $this->getValueParsed();
                if($value == 0)
                    $result = !$result;

                return $result;

            }
            
        }

        //validate with attribute is qty
        if($this->getAttribute() == 'qty')
        {
            $result = true;
            
            $qty = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();
            $value = $this->getValueParsed();
             switch ($op) {
                  case '==': case '!=':
                      $result = ($qty == $value);
                      break;
                  case '>=': case '<':
                      $result = ($qty >= $value);
                      break;
                  case '<=': case '>':
                      $result = ($qty <= $value);
                      break;
             }

             if($op == '!=' || $op == '<' || $op == '>')
                 $result = !$result;
            return $result;

        }

         //validate with attribute is qty
        if($this->getAttribute() == 'out_of_stock')
        {
            $result = true;
            
            if($product->isSaleable())
            {
                $result = false;
            }
            if($this->getValueParsed() == 0)
                $result = !$result;
            return $result;

        }

        //validate best seller
        if($this->getAttribute() == 'best_seller')
        {
            $result = true;
            $value = $this->getValueParsed();

            // Get Best Seller product
            $storeId    = Mage::app()->getStore()->getId();


            if(Mage::registry('bestSelletListId'))
                $bestSellerId = Mage::registry('bestSelletListId');
            else
            {
                $products = Mage::getResourceModel('reports/product_collection')
                    ->addOrderedQty()
                    //->addAttributeToSelect('*')
                    //->addAttributeToSelect(array('name', 'price', 'small_image', 'short_description', 'description')) //edit to suit tastes
                    ->setStoreId($storeId)
                    ->addStoreFilter($storeId)
                    ->setOrder('ordered_qty', 'desc') //best sellers on top
                    ->setPageSize($value);
                 Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
                 //Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
                 
                 $bestSellerId = array();
                 foreach($products as $p){
                      $bestSellerId[] = $p->getId();
                 }
				 if(!empty($bestSellerId))
					Mage::register('bestSelletListId',$bestSellerId);
            }
           

          
            if(!count($bestSellerId))
                $result = false;
            else{
                if($product->getTypeId() == 'configurable'){
					$childProducts = Mage::getModel('catalog/product_type_configurable')
					->getUsedProducts(null,$product);
				}
				elseif($product->getTypeId() == 'grouped'){
					$childProducts = Mage::getModel('catalog/product_type_grouped')->getAssociatedProducts($product);
				}
				elseif($product->getTypeId() == 'bundle'){
					$childProducts = $product->getTypeInstance(true)->getSelectionsCollection(
						$product->getTypeInstance(true)->getOptionsIds($product), $product
					);
				}
				else{
					if(!in_array($product->getId(), $bestSellerId))
						return false;
				}
				$flag = 0;
				if(isset($childProducts)){
					foreach($childProducts as $c){
						if(in_array($c->getId(),$bestSellerId))
						{
							$flag = 1;
							break;
						}
					}
					if($flag == 0)
						$result = false;
				}
            }
            
            return $result;

        }

        if(!$product->getData($this->getAttribute()) && ('!='==$op || '>'==$op || '<'==$op || '!{}'==$op || '!()'==$op ))
            return false;
        return parent::validate($product);
    }



    public function getAttributeName()
    {
        $attributeName = array('is_new' => 'Is new','is_special' => 'Is special','qty' => 'Qty','out_of_stock' => 'Is Out Of Stock','best_seller' => 'Amount Best seller');
        if(!array_key_exists($this->getAttribute(),$attributeName))
            return parent::getAttributeName ();
        return $attributeName[$this->getAttribute()];
    }

    public function getValueSelectOptions()
    {
        $attributeName = array('is_new' => 'Is new','is_special' => 'Is special','qty' => 'Qty','out_of_stock' => 'Is Out Of Stock');
        if(!array_key_exists($this->getAttribute(),$attributeName))
            return parent::getValueSelectOptions ();
        return array(
            
            array(
                'label' => 'No',
                'value' => 0
                ),
            array(
                'label' => 'Yes',
                'value' => 1
                ),
        );
    }

    public function getValueElementType()
    {
        $attributeName = array('is_new' => 'Is new','is_special' => 'Is special','out_of_stock' => 'Is Out Of Stock');
        if($this->getAttribute() == 'qty')
            return 'text';
        elseif(!array_key_exists($this->getAttribute(),$attributeName))
            return parent::getValueElementType ();
        return 'select';
    }

    public function getOperatorSelectOptions()
    {
        $attributeName = array('is_new' => 'Is new','is_special' => 'Is special','out_of_stock' => 'Is Out Of Stock','best_seller' => 'Amount Best seller');
        if($this->getAttribute() == 'qty')
            return array(
                array(
                    'label' => 'is',
                    'value' => '=='
                ),
                array(
                    'label' => 'is not',
                    'value' => '!='
                ),
                array(
                    'label' => 'equals or greater than',
                    'value' => '>='
                ),
                array(
                    'label' => 'equals or less than',
                    'value' => '<='
                ),
                array(
                    'label' => 'greater than',
                    'value' => '>'
                ),
                array(
                    'label' => 'less than',
                    'value' => '<'
                )
            );
        if(!array_key_exists($this->getAttribute(),$attributeName))
            return parent::getOperatorSelectOptions ();
        return array(
            array(
                'label' => 'is',
                'value' => '=='
                )
           /* array(
                'label' => 'is not',
                'value' => '!='
                )*/
        );
    }
}

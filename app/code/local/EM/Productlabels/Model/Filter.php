<?php
class EM_Productlabels_Model_Filter extends Mage_Widget_Model_Template_Filter
{
    /**
     * Filter the string as template.
     *
     * @param string $value
     * @return string
     */
    public function filter($object)
    {
        if(!is_string($object))
        {
            $value = $object->getText();
            $product = $object->getProduct();
        }
        else
            $value = $object;
        $customVariables = $this->getCustomVariable();
        // "depend" and "if" operands should be first
        foreach (array(
            self::CONSTRUCTION_DEPEND_PATTERN => 'dependDirective',
            self::CONSTRUCTION_IF_PATTERN     => 'ifDirective',
            ) as $pattern => $directive) {
            if (preg_match_all($pattern, $value, $constructions, PREG_SET_ORDER)) {
                foreach($constructions as $index => $construction) {
                    $replacedValue = '';
                    $callback = array($this, $directive);
                    if(!is_callable($callback)) {
                        continue;
                    }
                    try {
                        $replacedValue = call_user_func($callback, $construction);
                    } catch (Exception $e) {
                        throw $e;
                    }
                    $value = str_replace($construction[0], $replacedValue, $value);
                }
            }
        }

        if(preg_match_all(self::CONSTRUCTION_PATTERN, $value, $constructions, PREG_SET_ORDER)) {
            
            foreach($constructions as $index=>$construction) {
                $replacedValue = '';
                $callback = array($this, $construction[1].'Directive');
                if(!is_callable($callback)) {
                    continue;
                }
                try {
					$replacedValue = call_user_func($callback, $construction);

                                        if(in_array($construction[0], $customVariables))
                                        {
                                            $replacedValue = $this->getCustomVariableValue($construction,$product);
                                        }
                } catch (Exception $e) {
                	throw $e;
                }
                $value = str_replace($construction[0], $replacedValue, $value);
            }
        }
        return $value;
    }

    public function getCustomVariable()
    {
        return array(
            '{{var save_percent}}',
            '{{var save_price}}',
            '{{var product.price}}',
            '{{var product.special_price}}',
            '{{var product.qty}}'
        );
    }

    public function getCustomVariableValue($construction,$_product)
    {
        $type = trim($construction[2]);
        if($type == 'save_percent')
        {
            $specialPrice = $_product->getSpecialPrice();
            $regularPrice = $_product->getPrice();
            if($specialPrice > 0 && $regularPrice != 0)
                return number_format(100*(float)($regularPrice-$specialPrice)/$regularPrice,0);
            else
                return 0;
        }
        elseif($type == 'save_price'){
            $specialPrice = $_product->getSpecialPrice();
            if($specialPrice > 0)
                return Mage::helper('core')->currency($_product->getPrice() - $specialPrice);
            else
                return Mage::helper('core')->currency(0);
        }
        elseif($type == 'product.price')
        {
            return Mage::helper('core')->currency($_product->getPrice());
        }
        elseif($type == 'product.special_price'){
            return Mage::helper('core')->currency($_product->getSpecialPrice());
        }
        else{
            $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product);
            $qty = $stock->getQty();
            if($stock->getIsQtyDecimal() == 0)
                $qty = (int)$qty;
            return $qty;
        }
    }
}
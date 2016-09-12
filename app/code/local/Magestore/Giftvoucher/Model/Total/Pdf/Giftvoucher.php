<?php

class Magestore_Giftvoucher_Model_Total_Pdf_Giftvoucher extends Mage_Sales_Model_Order_Pdf_Total_Default
{
	public function getTotalsForDisplay(){
		$amount = $this->getAmount();
		$fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
		if(floatval($amount)){
			$amount = $this->getOrder()->formatPriceTxt($amount);
			if ($this->getAmountPrefix()){
				$discount = $this->getAmountPrefix().$discount;
			}
			$totals = array(array(
				'label' => Mage::helper('giftvoucher')->__('Gift Card (%s):',$this->getGiftCodes()),
				'amount' => $amount,
				'font_size' => $fontSize,
				)
			);	
			return $totals;
		}
	}
	
	public function getAmount(){
        if ($this->getSource()->getGiftVoucherDiscount()) {
            return -$this->getSource()->getGiftVoucherDiscount();
        }
		return -$this->getOrder()->getGiftVoucherDiscount();
	}
	
	public function getGiftCodes(){
		return $this->getOrder()->getGiftCodes();
	}
}

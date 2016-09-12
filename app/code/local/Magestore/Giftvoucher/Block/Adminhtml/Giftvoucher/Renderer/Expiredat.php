<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher_Renderer_Expiredat
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Render customer info to grid column html
     * 
     * @param Varien_Object $row
     */
    public function render(Varien_Object $row)
    {
        return  Mage::getModel('core/date')->gmtDate('m/d/Y',strtotime($row->getExpiredAt()));
    }
}

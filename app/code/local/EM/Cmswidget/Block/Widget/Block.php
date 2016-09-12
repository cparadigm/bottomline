<?php
class EM_Cmswidget_Block_Widget_Block extends Mage_Cms_Block_Widget_Block
{
	protected function _construct()
    {
        $this->addData(array(
            'cache_lifetime' => 3600,
            'cache_tags'        => array(Mage_Core_Model_Store::CACHE_TAG, Mage_Cms_Model_Block::CACHE_TAG)
        ));
    }
	
	public function getCacheKeyInfo()
	{
		return array(
			$this->getData('block_id'),
			Mage::app()->getStore()->getId(),
			(int)Mage::app()->getStore()->isCurrentlySecure(),
			Mage::getDesign()->getPackageName(),
			Mage::getDesign()->getTheme('template')
		);
	}

    protected function _toHtml()
    {   
        $this->setTemplate('em_cms/widget/static_block/default.phtml');
        return parent::_toHtml();
    }
}
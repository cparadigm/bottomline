<?php
class EM_Tabs_Model_Widgetinstance extends Mage_Core_Model_Abstract
{
	/*
		Get List of Widget Instance
		return : array(
			array(
				'value'	=>	'',
				'label'	=>	''
			)
		)
	*/
	public function toOptionArray()
    {
		$options = array(array('value'=>'','label'=>Mage::helper('tabs')->__('Select Widget Instance')));
        $collection = Mage::getModel('widget/widget_instance')->getCollection()
						->addFieldToFilter('instance_type',array('neq'=>'tabs/group'));
		
        if($collection->count()){
			foreach($collection as $instance){
				$options[] = array(
					'value'	=>	$instance->getId(),
					'label'	=>	$instance->getTitle()
				);
			}			
		}        
		
		return $options;
    }
}
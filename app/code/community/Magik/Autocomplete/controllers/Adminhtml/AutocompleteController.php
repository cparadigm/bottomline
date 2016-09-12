<?php

class Magik_Autocomplete_Adminhtml_AutocompleteController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('autocomplete/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	
	/*
	* Function to save the settings specified by the Admin
	*/
	public function magikAutoSuggestAction()
	{
		try {
			$post = $this->getRequest()->getPost();
			$resource = Mage::getSingleton('core/resource');
			$write= $resource->getConnection('core_write');
			$autocompleteTable = $resource->getTableName('autocomplete'); 
			
			if(isset($_FILES['fload']['name']) && $_FILES['fload']['name'] != '') {
				try {	
					
					$uploader = new Varien_File_Uploader('fload');
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);					
					$uploader->setFilesDispersion(false);					
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['fload']['name'] );
					
				} catch (Exception $e) { }
		      		$fname = $_FILES['fload']['name'];
		        }	        
		        else { $fname=""; } 	
			
			if($post['nop']!='')
				$nop=$post['nop'];
			else
				$nop=5;
			if($fname!=""){
			$query = "update ".$autocompleteTable." set no_products='".$nop."',header='".$post['hcont']."',footer='".$post['fcont']."',filename='".$fname."',tempsrc='".addslashes($post['tempsrc'])."',update_time='".date('Y-m-d H:i:s')."'"; 
			} else {
			$query = "update ".$autocompleteTable." set no_products='".$nop."',header='".$post['hcont']."',footer='".$post['fcont']."',tempsrc='".addslashes($post['tempsrc'])."',update_time='".date('Y-m-d H:i:s')."'";
			}
			$write->query($query);				
			$message = $this->__('Configuration saved successfully.');
			Mage::getSingleton('adminhtml/session')->addSuccess($message);		      
			

		}
		catch (Exception $e)
		{
			$message = $this->__('Unable to save.');
			Mage::getSingleton('adminhtml/session')->addError($message);      	
		}
		$this->_redirect('*/*/');
	}
	
	
	
	
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('autocomplete/autocomplete')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('autocomplete_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('autocomplete/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('autocomplete/adminhtml_autocomplete_edit'))
				->_addLeft($this->getLayout()->createBlock('autocomplete/adminhtml_autocomplete_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('autocomplete')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) { 
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}
	  			
	  			
			$model = Mage::getModel('autocomplete/autocomplete');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('autocomplete')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('autocomplete')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	
}

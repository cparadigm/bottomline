<?php
class EM_Productlabels_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $labels;
    public function resizeImage($imageName, $width=NULL, $height=NULL, $imagePath=NULL,$type)
   {
       $imagePath = str_replace("/", DS, $imagePath);
       $imagePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $imageName;

       if($width == NULL && $height == NULL) {
          $width = 100;
          $height = 100;
       }
       $resizePath = $width . 'x' . $height;
       $resizePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $type. DS . $resizePath . DS . $imageName;

       if (file_exists($imagePathFull) && !file_exists($resizePathFull)) {
          $imageObj = new Varien_Image($imagePathFull);
          $imageObj->keepTransparency(true);
          //$imageObj->constrainOnly(TRUE);
          $imageObj->keepAspectRatio(TRUE);
          $imageObj->resize($width,$height);
          $imageObj->save($resizePathFull);
       }

       $imagePath=str_replace(DS, "/", $imagePath);
       return Mage::getBaseUrl("media") . $imagePath . "/" . $type . "/" . $resizePath . "/"  . $imageName;
    }

    function initLabels()
    {
        if($this->labels)
            return $this->labels;
		$collection = Mage::getModel('productlabels/productlabels')->getCollection()->setStoreId(Mage::app()->getStore()->getId())
					->addAttributeToFilter('status',1)
					->addAttributeToSelect('*');
        $this->labels = $collection;
        return $this->labels;
    }

    function display($_product)
    {
        $labels = $this->initLabels();
        $block = new EM_Productlabels_Block_Productlabels();
        $labelShows = array();
        foreach ($labels as $label)
        {
            $catalogRule = Mage::getModel('productlabels/rule');
            $actionsArr = unserialize($label->getActions());
            if (!empty($actionsArr) && is_array($actionsArr)) {
                $catalogRule->getConditions()->loadArray($actionsArr);
            }
            $object = new Varien_Object();
            $object->setData('product', $_product);
            $object->setData('label', $label);
            
            if($validate = (bool)$catalogRule->validate($object)){
                $labelShows[] = $label;
            }

        }
        if(!empty ($labelShows)){
            echo $block->addObject(array('labels'=>$labelShows,'product'=>$_product))->setTemplate('em_productlabels/productlabels.phtml')->toHtml();
        }


        
    }

    /**
     * Default sore ID getter
     *
     * @return integer
     */
    protected function _getDefaultStoreId()
    {
        return Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID;
    }
}
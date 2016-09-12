<?php
class EM_Slideshow2_Block_Slideshow2 extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

	public function _toHtml(){
		$this->setTemplate('em_slideshow2/slideshow2.phtml');
		return parent::_toHtml();
	}

	public function getSlider()
    {
		$id	=	$this->getData('slideshow');
		$slider  = Mage::getModel('slideshow2/slider')->load($id)->getData();
		$slider['slider_params']	=	unserialize($slider['slider_params']);
		$slider['position']			=	unserialize($slider['position']);
		$slider['appearance']		=	unserialize($slider['appearance']);
		$slider['navigation']		=	unserialize($slider['navigation']);
		$slider['thumbnail']		=	unserialize($slider['thumbnail']);
		$slider['visibility']		=	unserialize($slider['visibility']);
		$slider['trouble']			=	unserialize($slider['trouble']);

		return $slider;
    }

	public function getImages($images)
    {
		$images	=	unserialize(base64_decode($images));
		
		return $images;
	}
	
	public function getResponsitiveValues($params){
		$sliderWidth = (int)$params['size_width'];
		$sliderHeight = (int)$params['size_height'];
		
		$percent = $sliderHeight / $sliderWidth;
		
		$w1 = (int)$params['screen_width_1'];
		$w2 = (int)$params['screen_width_2'];
		$w3 = (int)$params['screen_width_3'];
		$w4 = (int)$params['screen_width_4'];
		$w5 = (int)$params['screen_width_5'];
		$w6 = (int)$params['screen_width_6'];
		$w7 = (int)$params['screen_width_7'];
		$w8 = (int)$params['screen_width_8'];
		$w9 = (int)$params['screen_width_9'];
		$w10 = (int)$params['screen_width_10'];
		
		$sw1 = (int)$params['slider_width_1'];
		$sw2 = (int)$params['slider_width_2'];
		$sw3 = (int)$params['slider_width_3'];
		$sw4 = (int)$params['slider_width_4'];
		$sw5 = (int)$params['slider_width_5'];
		$sw6 = (int)$params['slider_width_6'];
		$sw7 = (int)$params['slider_width_7'];
		$sw8 = (int)$params['slider_width_8'];
		$sw9 = (int)$params['slider_width_9'];
		$sw10 = (int)$params['slider_width_10'];
		
		$arrItems = array();
		
		//add main item:
		$arr = array();				
		$arr["maxWidth"] = -1;
		$arr["minWidth"] = $w1;
		$arr["sliderWidth"] = $sliderWidth;
		$arr["sliderHeight"] = $sliderHeight;
		$arrItems[] = $arr;
		
		//add item 1:
		if(empty($w1))
			return($arrItems);
			
		$arr = array();				
		$arr["maxWidth"] = $w1-1;
		$arr["minWidth"] = $w2;
		$arr["sliderWidth"] = $sw1;
		$arr["sliderHeight"] = floor($sw1 * $percent);
		$arrItems[] = $arr;
		
		//add item 2:
		if(empty($w2))
			return($arrItems);
		
		$arr["maxWidth"] = $w2-1;
		$arr["minWidth"] = $w3;
		$arr["sliderWidth"] = $sw2;
		$arr["sliderHeight"] = floor($sw2 * $percent);
		$arrItems[] = $arr;
		
		//add item 3:
		if(empty($w3))
			return($arrItems);
		
		$arr["maxWidth"] = $w3-1;
		$arr["minWidth"] = $w4;
		$arr["sliderWidth"] = $sw3;
		$arr["sliderHeight"] = floor($sw3 * $percent);
		$arrItems[] = $arr;
		
		//add item 4:
		if(empty($w4))
			return($arrItems);
		
		$arr["maxWidth"] = $w4-1;
		$arr["minWidth"] = $w5;
		$arr["sliderWidth"] = $sw4;
		$arr["sliderHeight"] = floor($sw4 * $percent);
		$arrItems[] = $arr;

		//add item 5:
		if(empty($w5))
			return($arrItems);
		
		$arr["maxWidth"] = $w5-1;
		$arr["minWidth"] = $w6;
		$arr["sliderWidth"] = $sw5;
		$arr["sliderHeight"] = floor($sw5 * $percent);
		$arrItems[] = $arr;
		
		//add item 6:
		if(empty($w6))
			return($arrItems);
		
		$arr["maxWidth"] = $w6-1;
		$arr["minWidth"] = 0;
		$arr["sliderWidth"] = $sw6;
		$arr["sliderHeight"] = floor($sw6 * $percent);
		$arrItems[] = $arr;
		
		//add item 7:
		if(empty($w7))
			return($arrItems);
		
		$arr["maxWidth"] = $w7-1;
		$arr["minWidth"] = 0;
		$arr["sliderWidth"] = $sw7;
		$arr["sliderHeight"] = floor($sw7 * $percent);
		$arrItems[] = $arr;
		
		//add item 8:
		if(empty($w8))
			return($arrItems);
		
		$arr["maxWidth"] = $w8-1;
		$arr["minWidth"] = 0;
		$arr["sliderWidth"] = $sw8;
		$arr["sliderHeight"] = floor($sw8 * $percent);
		$arrItems[] = $arr;
		
		//add item 9:
		if(empty($w9))
			return($arrItems);
		
		$arr["maxWidth"] = $w9-1;
		$arr["minWidth"] = 0;
		$arr["sliderWidth"] = $sw9;
		$arr["sliderHeight"] = floor($sw9 * $percent);
		$arrItems[] = $arr;
		
		//add item 10:
		if(empty($w10))
			return($arrItems);
		
		$arr["maxWidth"] = $w10-1;
		$arr["minWidth"] = 0;
		$arr["sliderWidth"] = $sw10;
		$arr["sliderHeight"] = floor($sw10 * $percent);
		$arrItems[] = $arr;
		
		return($arrItems);
	}
	
	public function getResizeImage($name,$width = 255, $height = 255){
		if(!$name) return;

		$imagePathFull = Mage::getBaseDir('media').DS.'em_slideshow'.DS.$name;
		$resizePath = $width . 'x' . $height;
		$resizePathFull = Mage::getBaseDir('media'). DS .'em_slideshow' . DS . 'resize' . DS . $resizePath . DS . $name;

		if (file_exists($imagePathFull) && !file_exists($resizePathFull)) {
			$imageObj = new Varien_Image($imagePathFull);
			$imageObj->constrainOnly(TRUE);
			$imageObj->resize($width,$height);
			$imageObj->save($resizePathFull);
		}

		return Mage::getBaseUrl('media'). 'em_slideshow/resize/' . $resizePath . "/"  . $name;	
	}

}
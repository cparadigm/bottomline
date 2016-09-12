<?php
class EM_Slideshow2_Block_Adminhtml_Slider_Edit_Tab_Images extends Mage_Adminhtml_Block_Widget_Form
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

	public function _toHtml(){
		$this->setTemplate('em_slideshow2/slider_img.phtml');

		$data	=	unserialize(base64_decode(Mage::registry('slideshow2_data')->getImages()));

		if($data)	$count	=	count($data);
		else	$count	=	0;

		$this->assign('count', $count);
		$this->assign('data', $data);

		return parent::_toHtml();
	}
	
	public function getSub($info){
		foreach($info as $key=>$val){
			if($val['text'] == "" )	unset($info[$key]);
		}
		$sub['count']	=	count($info);
		$sub['info']	=	$info;

		return $sub;
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
	
	public function getTransition($choose){
		if(!isset($choose))	$choose	=	"demo";
		$html  =	'';
		if($choose	==	'demo')
			$html .=	'<option value="demo" selected >Various Transitions</option>';
		else
			$html .=	'<option value="demo">Various Transitions</option>';
			
		if($choose	==	'boxslide')
			$html .=	'<option value="boxslide" selected>Box Mask</option>';
		else
			$html .=	'<option value="boxslide">Box Mask</option>';
		
		if($choose	==	'boxfade')
			$html .=	'<option value="boxfade" selected>Box Mask Mosaic</option>';
		else
			$html .=	'<option value="boxfade">Box Mask Mosaic</option>';
		
		if($choose	==	'slotzoom-horizontal')
			$html .=	'<option value="slotzoom-horizontal" selected>Slot Zoom Horizontal</option>';
		else
			$html .=	'<option value="slotzoom-horizontal">Slot Zoom Horizontal</option>';
		
		if($choose	==	'slotslide-horizontal')
			$html .=	'<option value="slotslide-horizontal" selected>Slot Slide Horizontal</option>';
		else
			$html .=	'<option value="slotslide-horizontal">Slot Slide Horizontal</option>';
			
		if($choose	==	'slotfade-horizontal')
			$html .=	'<option value="slotfade-horizontal" selected>Slot Fade Horizontal</option>';
		else	
			$html .=	'<option value="slotfade-horizontal">Slot Fade Horizontal</option>';
			
		if($choose	==	'slotzoom-vertical')
			$html .=	'<option value="slotzoom-vertical" selected>Slot Zoom Vertical</option>';
		else	
			$html .=	'<option value="slotzoom-vertical">Slot Zoom Vertical</option>';
			
		if($choose	==	'slotslide-vertical')
			$html .=	'<option value="slotslide-vertical" selected>Slot Slide Vertical</option>';
		else
			$html .=	'<option value="slotslide-vertical">Slot Slide Vertical</option>';
			
		if($choose	==	'slotfade-vertical')
			$html .=	'<option value="slotfade-vertical" selected>Slot Fade Vertical</option>';
		else
			$html .=	'<option value="slotfade-vertical">Slot Fade Vertical</option>';
			
		if($choose	==	'curtain-1')
			$html .=	'<option value="curtain-1" selected>Curtain One</option>';
		else
			$html .=	'<option value="curtain-1">Curtain One</option>';
			
		if($choose	==	'curtain-2')
			$html .=	'<option value="curtain-2" selected>Curtain Two</option>';
		else
			$html .=	'<option value="curtain-2">Curtain Two</option>';
			
		if($choose	==	'curtain-3')
			$html .=	'<option value="curtain-3" selected>Curtain Three</option>';
		else
			$html .=	'<option value="curtain-3">Curtain Three</option>';
			
		if($choose	==	'slideleft')
			$html .=	'<option value="slideleft" selected>Slide Left</option>';
		else
			$html .=	'<option value="slideleft">Slide Left</option>';
			
		if($choose	==	'slideright')
			$html .=	'<option value="slideright" selected>Slide Right</option>';
		else
			$html .=	'<option value="slideright">Slide Right</option>';
			
		if($choose	==	'slideup')
			$html .=	'<option value="slideup" selected>Slide Up</option>';
		else
			$html .=	'<option value="slideup">Slide Up</option>';
			
		if($choose	==	'slidedown')
			$html .=	'<option value="slidedown" selected>Slide Down</option>';
		else
			$html .=	'<option value="slidedown">Slide Down</option>';
		
		if($choose	==	'fade')
			$html .=	'<option value="fade" selected>Fade</option>';
		else
			$html .=	'<option value="fade">Fade</option>';
			
		if($choose	==	'flyin')
			$html .=	'<option value="flyin" selected>Fly In</option>';
		else
			$html .=	'<option value="flyin">Fly In</option>';
			
		if($choose	==	'cubic')
			$html .=	'<option value="cubic" selected>Cubic</option>';
		else
			$html .=	'<option value="cubic">Cubic</option>';
			
		if($choose	==	'turnoff')
			$html .=	'<option value="turnoff" selected>Turn Off</option>';
		else
			$html .=	'<option value="turnoff">Turn Off</option>';
			
		if($choose	==	'3dcurtain-horizontal')
			$html .=	'<option value="3dcurtain-horizontal" selected>3D Curtain Horizontal</option>';
		else
			$html .=	'<option value="3dcurtain-horizontal">3D Curtain Horizontal</option>';
			
		if($choose	==	'3dcurtain-vertical')
			$html .=	'<option value="3dcurtain-vertical" selected>3D Curtain Vertical</option>';
		else
			$html .=	'<option value="3dcurtain-vertical">3D Curtain Vertical</option>';
			
		if($choose	==	'papercut')
			$html .=	'<option value="papercut" selected>Paper Cut</option>';
		else
			$html .=	'<option value="papercut">Paper Cut</option>';
			
		return $html;
	}
	
	public function getEasing($choose){
		if(!isset($choose))	$choose	=	"easeOutBack";
		$html  =	'';
		if($choose	==	'easeOutBack')
			$html .=	'<option value="easeOutBack" selected>easeOutBack</option>';
		else
			$html .=	'<option value="easeOutBack">easeOutBack</option>';

		if($choose	==	'easeInQuad')
			$html .=	'<option value="easeInQuad" selected>easeInQuad</option>';
		else
			$html .=	'<option value="easeInQuad">easeInQuad</option>';

		if($choose	==	'easeOutQuad')
			$html .=	'<option value="easeOutQuad" selected>easeOutQuad</option>';
		else
			$html .=	'<option value="easeOutQuad">easeOutQuad</option>';

		if($choose	==	'easeInOutQuad')
			$html .=	'<option value="easeInOutQuad" selected>easeInOutQuad</option>';
		else
			$html .=	'<option value="easeInOutQuad">easeInOutQuad</option>';

		if($choose	==	'easeInCubic')
			$html .=	'<option value="easeInCubic" selected>easeInCubic</option>';
		else
			$html .=	'<option value="easeInCubic">easeInCubic</option>';

		if($choose	==	'easeOutCubic')
			$html .=	'<option value="easeOutCubic" selected>easeOutCubic</option>';
		else
			$html .=	'<option value="easeOutCubic">easeOutCubic</option>';

		if($choose	==	'easeInOutCubic')
			$html .=	'<option value="easeInOutCubic" selected>easeInOutCubic</option>';
		else
			$html .=	'<option value="easeInOutCubic">easeInOutCubic</option>';

		if($choose	==	'easeInQuart')
			$html .=	'<option value="easeInQuart" selected>easeInQuart</option>';
		else
			$html .=	'<option value="easeInQuart">easeInQuart</option>';

		if($choose	==	'easeOutQuart')
			$html .=	'<option value="easeOutQuart" selected>easeOutQuart</option>';
		else
			$html .=	'<option value="easeOutQuart">easeOutQuart</option>';

		if($choose	==	'easeInOutQuart')
			$html .=	'<option value="easeInOutQuart" selected>easeInOutQuart</option>';
		else
			$html .=	'<option value="easeInOutQuart">easeInOutQuart</option>';

		if($choose	==	'easeInQuint')
			$html .=	'<option value="easeInQuint" selected>easeInQuint</option>';
		else
			$html .=	'<option value="easeInQuint">easeInQuint</option>';

		if($choose	==	'easeOutQuint')
			$html .=	'<option value="easeOutQuint" selected>easeOutQuint</option>';
		else
			$html .=	'<option value="easeOutQuint">easeOutQuint</option>';

		if($choose	==	'easeInOutQuint')
			$html .=	'<option value="easeInOutQuint" selected>easeInOutQuint</option>';
		else
			$html .=	'<option value="easeInOutQuint">easeInOutQuint</option>';

		if($choose	==	'easeInSine')
			$html .=	'<option value="easeInSine" selected>easeInSine</option>';
		else
			$html .=	'<option value="easeInSine">easeInSine</option>';

		if($choose	==	'easeOutSine')
			$html .=	'<option value="easeOutSine" selected>easeOutSine</option>';
		else
			$html .=	'<option value="easeOutSine">easeOutSine</option>';

		if($choose	==	'easeInOutSine')
			$html .=	'<option value="easeInOutSine" selected>easeInOutSine</option>';
		else
			$html .=	'<option value="easeInOutSine">easeInOutSine</option>';

		if($choose	==	'easeInExpo')
			$html .=	'<option value="easeInExpo" selected>easeInExpo</option>';
		else
			$html .=	'<option value="easeInExpo">easeInExpo</option>';

		if($choose	==	'easeOutExpo')
			$html .=	'<option value="easeOutExpo" selected>easeOutExpo</option>';
		else
			$html .=	'<option value="easeOutExpo">easeOutExpo</option>';

		if($choose	==	'easeInOutExpo')
			$html .=	'<option value="easeInOutExpo" selected>easeInOutExpo</option>';
		else
			$html .=	'<option value="easeInOutExpo">easeInOutExpo</option>';

		if($choose	==	'easeInCirc')
			$html .=	'<option value="easeInCirc" selected>easeInCirc</option>';
		else
			$html .=	'<option value="easeInCirc">easeInCirc</option>';

		if($choose	==	'easeOutCirc')
			$html .=	'<option value="easeOutCirc" selected>easeOutCirc</option>';
		else
			$html .=	'<option value="easeOutCirc">easeOutCirc</option>';

		if($choose	==	'easeInOutCirc')
			$html .=	'<option value="easeInOutCirc" selected>easeInOutCirc</option>';
		else
			$html .=	'<option value="easeInOutCirc">easeInOutCirc</option>';

		if($choose	==	'easeInElastic')
			$html .=	'<option value="easeInElastic" selected>easeInElastic</option>';
		else
			$html .=	'<option value="easeInElastic">easeInElastic</option>';

		if($choose	==	'easeOutElastic')
			$html .=	'<option value="easeOutElastic" selected>easeOutElastic</option>';
		else
			$html .=	'<option value="easeOutElastic">easeOutElastic</option>';

		if($choose	==	'easeInOutElastic')
			$html .=	'<option value="easeInOutElastic" selected>easeInOutElastic</option>';
		else
			$html .=	'<option value="easeInOutElastic">easeInOutElastic</option>';

		if($choose	==	'easeInBack')
			$html .=	'<option value="easeInBack" selected>easeInBack</option>';
		else
			$html .=	'<option value="easeInBack">easeInBack</option>';

		if($choose	==	'easeInOutBack')
			$html .=	'<option value="easeInOutBack" selected>easeInOutBack</option>';
		else
			$html .=	'<option value="easeInOutBack">easeInOutBack</option>';

		if($choose	==	'easeInBounce')
			$html .=	'<option value="easeInBounce" selected>easeInBounce</option>';
		else
			$html .=	'<option value="easeInBounce">easeInBounce</option>';

		if($choose	==	'easeOutBounce')
			$html .=	'<option value="easeOutBounce" selected>easeOutBounce</option>';
		else
			$html .=	'<option value="easeOutBounce">easeOutBounce</option>';

		if($choose	==	'easeInOutBounce')
			$html .=	'<option value="easeInOutBounce" selected>easeInOutBounce</option>';
		else
			$html .=	'<option value="easeInOutBounce">easeInOutBounce</option>';

		return $html;
	}

	public function getAnimation($choose){
		if(!isset($choose))	$choose	=	"fade";
		$html  =	'';
		if($choose	==	'fade')
			$html .=	'<option value="fade" selected>Fade</option>';
		else
			$html .=	'<option value="fade">Fade</option>';

		if($choose	==	'sft')
			$html .=	'<option value="sft" selected>Short from Top</option>';
		else
			$html .=	'<option value="sft">Short from Top</option>';
			
		if($choose	==	'sfb')
			$html .=	'<option value="sfb" selected>Short from Bottom</option>';
		else
			$html .=	'<option value="sfb">Short from Bottom</option>';
			
		if($choose	==	'sfr')
			$html .=	'<option value="sfr" selected>Short from Right</option>';
		else
			$html .=	'<option value="sfr">Short from Right</option>';
			
		if($choose	==	'sfl')
			$html .=	'<option value="sfl" selected>Short from Left</option>';
		else
			$html .=	'<option value="sfl">Short from Left</option>';
			
		if($choose	==	'lft')
			$html .=	'<option value="lft" selected>Long from Top</option>';
		else
			$html .=	'<option value="lft">Long from Top</option>';
			
		if($choose	==	'lfb')
			$html .=	'<option value="lfb" selected>Long from Bottom</option>';
		else
			$html .=	'<option value="lfb">Long from Bottom</option>';
			
		if($choose	==	'lfr')
			$html .=	'<option value="lfr" selected>Long from Right</option>';
		else
			$html .=	'<option value="lfr">Long from Right</option>';
			
		if($choose	==	'lfl')
			$html .=	'<option value="lfl" selected>Long from Left</option>';
		else
			$html .=	'<option value="lfl">Long from Left</option>';
			
		if($choose	==	'randomrotate')
			$html .=	'<option value="randomrotate" selected>Random Rotate</option>';
		else
			$html .=	'<option value="randomrotate">Random Rotate</option>';

		return $html;
	}
}
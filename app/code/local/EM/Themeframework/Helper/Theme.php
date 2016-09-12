<?php
/**
 * EMThemes
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
 * Do not edit or add to this file if you wish to upgrade the framework to newer
 * versions in the future. If you wish to customize the framework for your
 * needs please refer to http://www.emthemes.com/ for more information.
 *
 * @category    EM
 * @package     EM_ThemeFramework
 * @copyright   Copyright (c) 2012 CodeSpot JSC. (http://www.emthemes.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      Giao L. Trinh (giao.trinh@emthemes.com)
 */

class EM_Themeframework_Helper_Theme {
	
	function display($root, $layout = '1column') {
		$cookie = Mage::getSingleton('core/cookie');
		$isPreviewBlock = $cookie->get('EDIT_BLOCK_KEY') != '' && $cookie->get('adminhtml') != '';
		$isPreviewArea = $cookie->get('PREVIEW_AREA') != '' && $cookie->get('adminhtml') != '';
		
		$collection = Mage::getModel('themeframework/area')->getCollection()
						->addStoreFilter(Mage::app()->getStore()->getId())
						->addFilter('layout', array('eq' => $layout))
						->addFilter('is_active', array('eq' => 1))
						->addOrder('store_id', 'DESC');
		$package_theme = Mage::getSingleton('core/design_package')->getPackageName().'/'.Mage::getSingleton('core/design_package')->getTheme('frontend');
		
		if($package_theme != '')
		{
			$collection->addFilter('package_theme ', array('eq' => $package_theme ));
		}
		$model = $collection->getFirstItem();
		
		$content = unserialize($model->getContent());
		$html = '';
		foreach ($content as $div) {
			$containerHtml = '';
			
			// div.container_24
			if ($div['type'] == 'container_24') {
				$gridHtml = '';
				foreach ($div['items'] as $grid) {
					// div.clear
					if (is_string($grid) && $grid == 'clear') {
						$gridHtml .= '<div class="clear"></div>';
					// div.grid_*
					} elseif (is_array($grid)) {
						$class = array('grid_'.$grid['column']);
						if ($grid['push']) $class[] = 'push_'.$grid['push'];
						if ($grid['pull']) $class[] = 'pull_'.$grid['pull'];
						if ($grid['prefix']) $class[] = 'prefix_'.$grid['prefix'];
						if ($grid['suffix']) $class[] = 'suffix_'.$grid['suffix'];
						if ($grid['first']) $class[] = 'alpha';
						if ($grid['last']) $class[] = 'omega';
						if ($grid['custom_css']) $class[] = $grid['custom_css'];
						$class = implode(' ', $class);
						
						
						// blocks
						$blockHtml = '';
						foreach ($grid['items'] as $blockName) {
							if ($isPreviewArea)
								$blockHtml .= '<div class="em_themeframework_previewarea">'
									. '<div class="em_themeframework_previewarea_title">'.$blockName.'</div>'
									. trim($root->getChildHtml($blockName))
									. '</div>';
							else
								$blockHtml .= trim($root->getChildHtml($blockName));
							
						}
							
						if ($blockHtml == '' && !$grid['display_empty'])
							continue;
							
						if ($grid['inner_html'])
							$blockHtml = str_replace('{{content}}', $blockHtml, $grid['inner_html']);
							
						$gridHtml .= '<div class="'.$class.'">'.$blockHtml.'</div>';
					}
				}
				
				if ($gridHtml == '' && !$div['display_empty'])
					continue;
				
				if ($div['inner_html'])
					$gridHtml = str_replace('{{content}}', $gridHtml, $div['inner_html']);
				
				$containerHtml .= '<div class="container_24 '.$div['custom_css'].'">';
				$containerHtml .= $gridHtml;	
				$containerHtml .= '</div>';
			}
			
			// free div
			else {
				$blockHtml = '';
				foreach ($div['items'] as $blockName) {
					if ($isPreviewArea)
						$blockHtml .= '<div class="em_themeframework_previewarea">'
							. '<div class="em_themeframework_previewarea_title">'.$blockName.'</div>'
							. trim($root->getChildHtml($blockName))
							. '</div>';
					else
						$blockHtml .= trim($root->getChildHtml($blockName));
				}
				
				if ($blockHtml == '' && !$div['display_empty'])
					continue;
					
				if ($div['inner_html'])
					$blockHtml = str_replace('{{content}}', $blockHtml, $div['inner_html']);
				
				$containerHtml .= '<div class="'.$div['custom_css'].'">';
				$containerHtml .= $blockHtml;
				$containerHtml .= '</div>';
			}
			
			if ($div['outer_html'])
				$containerHtml = str_replace('{{content}}', $containerHtml, $div['outer_html']);
			
			$html .= $containerHtml;
		}
		
		if ($isPreviewBlock || $isPreviewArea) {
			$html .= "<script type=\"text/javascript\" src=\"".$root->getSkinUrl('js/em_themeframework/em_themeframework.js')."\"></script>";
			$html .= "<script type=\"text/javascript\">jQuery(function($) { 
				$('head').append('<link rel=\"stylesheet\" type=\"text/css\" href=\"".$root->getSkinUrl('css/em_themeframework.css')."\"></link>');
				$('body').append('<div id=\"em_themeframework_previewblock_actions\"><a href=\"".Mage::getUrl('themeframework/area/disablePreview')."\" class=\"turnoff\">".$root->__("Disable Preview")."</a></div>');
			});</script>";
		}
		
		return $html;
	}
	
	function displayBootstrap($root, $layout = '1column') {
		$cookie = Mage::getSingleton('core/cookie');
		$isPreviewBlock = $cookie->get('EDIT_BLOCK_KEY') != '' && $cookie->get('adminhtml') != '';
		$isPreviewArea = $cookie->get('PREVIEW_AREA') != '' && $cookie->get('adminhtml') != '';
		//$isPreview = Mage::registry('is_preview');
		
		$collection = Mage::getModel('themeframework/area')->getCollection()
						->addStoreFilter(Mage::app()->getStore()->getId())
						->addFilter('layout', array('eq' => $layout))
						->addFilter('is_active', array('eq' => 1))
						->addOrder('store_id', 'DESC');
		$package_theme = Mage::getSingleton('core/design_package')->getPackageName().'/'.Mage::getSingleton('core/design_package')->getTheme('frontend');
		
		if($package_theme != '')
		{
			$collection->addFilter('package_theme ', array('eq' => $package_theme ));
		}
		$model = $collection->getFirstItem();
		
		
		$content = unserialize($model->getContent());
		$html = '';
		foreach ($content as $div) {
			$containerHtml = '';
			
			// div.container_24
			if ($div['type'] == 'container_24') {
				$spanHtml = '';
				$rowHtml = '';
				$col = 0;
				foreach ($div['items'] as $grid) {
					// div.clear
					if (is_string($grid) && $grid == 'clear') {
						if ($col > 0) {
							// finish a div.row
							$rowHtml .= '<div class="row'.($div['fluid'] ? '-fluid' : '').'">'.$spanHtml.'</div>';
							$spanHtml = '';
							$col = 0;
						}
					// div.grid_*
					} elseif (is_array($grid)) {
						
						// finish a div.row
						$col += $grid['column'];
						if ($col > 24) {
							$rowHtml .= '<div class="row'.($div['fluid'] ? '-fluid' : '').'">'.$spanHtml.'</div>';
							$spanHtml = '';
							$col = $grid['column'];
						}
						
						$class = array('span'.$grid['column']);
						if ($grid['push']) $class[] = 'push_'.$grid['push'];
						if ($grid['pull']) $class[] = 'pull_'.$grid['pull'];
						if ($grid['prefix']) $class[] = 'offset'.$grid['prefix'];
						if ($grid['suffix']) $class[] = 'suffix_'.$grid['suffix'];
						if ($grid['first']) $class[] = 'alpha';
						if ($grid['last']) $class[] = 'omega';
						if ($grid['custom_css']) $class[] = $grid['custom_css'];
						$class = implode(' ', $class);
						
						
						// blocks
						$blockHtml = '';
						$debugTitle = '';
						foreach ($grid['items'] as $blockName) {
							//if (empty($firstBlockName)) $debugTitle = '<div class="dbg-title">'.$blockName.'</div>';
							if ($isPreviewArea)
								$blockHtml .= '<div class="em_themeframework_previewarea">'
									. '<div class="em_themeframework_previewarea_title">'.$blockName.'</div>'
									. trim($root->getChildHtml($blockName))
									. '</div>';
							else		
								$blockHtml .= trim($root->getChildHtml($blockName));
						}
							
						if ($blockHtml == '' && !$grid['display_empty'])
							continue;
							
						if ($grid['inner_html'])
							$blockHtml = str_replace('{{content}}', $blockHtml, $grid['inner_html']);
						$spanHtml .= '<div class="'.$class.'">'.$blockHtml.'</div>';
					}
				}
				
				if ($col > 0) {
					// finish a div.row
					$rowHtml .= '<div class="row'.($div['fluid'] ? '-fluid' : '').'">'.$spanHtml.'</div>';
					$spanHtml = '';
				}
				
				if ($rowHtml == '' && !$div['display_empty'])
					continue;
				
				if ($div['inner_html'])
					$rowHtml = str_replace('{{content}}', $rowHtml, $div['inner_html']);
				
				$containerHtml .= '<div class="container'.($div['fluid'] ? '-fluid ' : ' ').$div['custom_css'].'">';
				$containerHtml .= $rowHtml;	
				$containerHtml .= '</div>';
			}
			
			// free div
			else {
				$blockHtml = '';
				$debugTitle = '';
				foreach ($div['items'] as $blockName) {
					//if (empty($firstBlockName)) $debugTitle = '<div class="dbg-title">'.$blockName.'</div>';
					if ($isPreviewArea)
						$blockHtml .= '<div class="em_themeframework_previewarea">'
							. '<div class="em_themeframework_previewarea_title">'.$blockName.'</div>'
							. trim($root->getChildHtml($blockName))
							. '</div>';
					else
						$blockHtml .= trim($root->getChildHtml($blockName));
				}
				
				if ($blockHtml == '' && !$div['display_empty'])
					continue;
					
				if ($div['inner_html'])
					$blockHtml = str_replace('{{content}}', $blockHtml, $div['inner_html']);
				
				$containerHtml .= '<div class="'.$div['custom_css'].'">';
				$containerHtml .= $blockHtml;
				$containerHtml .= '</div>';
			}
			
			if ($div['outer_html'])
				$containerHtml = str_replace('{{content}}', $containerHtml, $div['outer_html']);
			
			$html .= $containerHtml;
		}
		
		
		if ($isPreviewBlock || $isPreviewArea) {
			$html .= "<script type=\"text/javascript\" src=\"".$root->getSkinUrl('js/em_themeframework/em_themeframework.js')."\"></script>";
			$html .= "<script type=\"text/javascript\">jQuery(function($) { 
				$('head').append('<link rel=\"stylesheet\" type=\"text/css\" href=\"".$root->getSkinUrl('css/em_themeframework.css')."\"></link>');
				$('body').append('<div id=\"em_themeframework_previewblock_actions\"><a href=\"".Mage::getUrl('themeframework/area/disablePreview')."\" class=\"turnoff\">".$root->__("Disable Preview")."</a></div>');
			});</script>";
		}
		
		return $html;
	}
}

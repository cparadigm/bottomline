<?php
class EM_Tabs_Model_Widget extends Mage_Widget_Model_Widget
{
	/**
     * Return widget presentation code in WYSIWYG editor
     *
     * @param string $type Widget Type
     * @param array $params Pre-configured Widget Params
     * @param bool $asIs Return result as widget directive(true) or as placeholder image(false)
     * @return string Widget directive ready to parse
     */
    public function getWidgetDeclaration($type, $params = array(), $asIs = true)
    {
		if($type != 'tabs/group'){
			for($i = 1;$i<=10;$i++){
				if(isset($params['title_'.$i]) && is_array($params['title_'.$i]))
					unset($params['title_'.$i]);
			}
			return parent::getWidgetDeclaration($type, $params, $asIs);
		}	
        $directive = '{{widget type="' . $type . '"';

        foreach ($params as $name => $value) {
            // Retrieve default option value if pre-configured
			if (is_array($value)) {
				if($type == 'tabs/group'){
					if(implode('',$value))
						$value = base64_encode(serialize($value));
					else
						$value = '';
				}
				else
					$value = implode(',', $value);
            } elseif (trim($value) == '') {
                $widget = $this->getConfigAsObject($type);
                $parameters = $widget->getParameters();
                if (isset($parameters[$name]) && is_object($parameters[$name])) {
                    $value = $parameters[$name]->getValue();
                }
            }
            if ($value) {
                $directive .= sprintf(' %s="%s"', $name, $value);
            }
        }
        $directive .= '}}';

        if ($asIs) {
            return $directive;
        }

        $config = Mage::getSingleton('widget/widget_config');
        $imageName = str_replace('/', '__', $type) . '.gif';
        if (is_file($config->getPlaceholderImagesBaseDir() . DS . $imageName)) {
            $image = $config->getPlaceholderImagesBaseUrl() . $imageName;
        } else {
            $image = $config->getPlaceholderImagesBaseUrl() . 'default.gif';
        }
        $html = sprintf('<img id="%s" src="%s" title="%s">',
            $this->_idEncode($directive),
            $image,
            Mage::helper('core')->urlEscape($directive)
        );
        return $html;
    }
}
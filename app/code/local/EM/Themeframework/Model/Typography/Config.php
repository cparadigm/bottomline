<?php
class EM_Themeframework_Model_Typography_Config
{
    /**
     * Prepare variable wysiwyg config
     *
     * @param Varien_Object $config
     * @return array
     */
    public function getWysiwygPluginSettings($config)
    {
        $variableConfig = array();
        $onclickParts = array(
            'search' => array('html_id'),
            'subject' => 'MagentotypoPlugin.loadChooser(\''.$this->getTyposWysiwygActionUrl().'\', \'{{html_id}}\');'
        );

        $variableWysiwygPlugin = array(array('name' => 'magentotypo',
            'src' => $this->getWysiwygJsPluginSrc(),
            'options' => array(
                'title' => Mage::helper('adminhtml')->__('Insert Typography...'),
                'url' => $this->getTyposWysiwygActionUrl(),
                'onclick' => $onclickParts,
                'class'   => 'add-typo plugin'
            )));

        $configPlugins = $config->getData('plugins');
        $variableConfig['plugins'] = array_merge($configPlugins, $variableWysiwygPlugin);
        return $variableConfig;
    }

    /**
     * Return url to wysiwyg plugin
     *
     * @return string
     */
    public function getWysiwygJsPluginSrc()
    {
        return Mage::getBaseUrl('js').'mage/adminhtml/wysiwyg/tiny_mce/plugins/magentotypo/editor_plugin.js';
    }

    /**
     * Return url of action to get variables
     *
     * @return string
     */
    public function getTyposWysiwygActionUrl()
    {
        return Mage::getSingleton('adminhtml/url')->getUrl('themeframework/adminhtml_system_typography/wysiwygPlugin');
    }
}
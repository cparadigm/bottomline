/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

tinyMCE.addI18n({en:{
    magentotypo:
    {
        insert_typo : "Insert Typography"
    }
}});

(function() {
    tinymce.create('tinymce.plugins.MagentotypoPlugin', {
        /**
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            ed.addCommand('mceMagentotypo', function() {
                var pluginSettings = ed.settings.magentoPluginsOptions.get('magentotypo');
                MagentotypoPlugin.setEditor(ed);
                MagentotypoPlugin.loadChooser(pluginSettings.url, null);
            });

            // Register Widget plugin button
            ed.addButton('magentotypo', {
                title : 'magentotypo.insert_typo',
                cmd : 'mceMagentotypo',
                image : url + '/img/icon.gif'
            });
        },

        getInfo : function() {
            return {
                longname : 'Magento Typography Manager Plugin for TinyMCE 3.x',
                author : 'Magento Core Team',
                authorurl : 'http://magentocommerce.com',
                infourl : 'http://magentocommerce.com',
                version : "1.0"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('magentotypo', tinymce.plugins.MagentotypoPlugin);
})();

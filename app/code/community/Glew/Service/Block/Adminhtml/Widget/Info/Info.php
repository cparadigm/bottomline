<?php

class Glew_Service_Block_Adminhtml_Widget_Info_Info extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = '<div style="background:url(\'http://glew.io/wp-content/uploads/2015/04/Glew-white_Artboard7.png\') no-repeat scroll 15px center #EAF0EE;background-size:160px;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 200px;">
                    <h4>About Glew.&trade;</h4>
                    <p>Glew.&trade; Empowers Marketers to Take Action, Increase Team Efficiency and Maximize Revenue.<br>
                    <br />
                    <table width="500px" border="0">
                        <tr>
                            <td>Questions, comments, need help? Send us an email.</td>
                            <td><a href="mailto:support@glew.io">support@glew.io</a></td>
                        </tr>
                        <tr>
                            <td height="30">Visit our website:</td>
                            <td><a href="http://glew.io" target="_blank">glew.io</a></td>
                        </tr>
                    </table>
                </div>';

        return $html;
    }
}

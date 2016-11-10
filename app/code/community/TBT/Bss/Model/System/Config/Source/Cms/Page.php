<?php

class TBT_Bss_Model_System_Config_Source_Cms_Page
{
    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $pageCollection = Mage::getResourceModel('cms/page_collection')->load();
            $res = array();

            foreach ($pageCollection as $item) {
                if ($item->getData('identifier') == "no-route") {
                    continue;
                }

                $pageId        = $item->getData( 'page_id' );
                $data['value'] = $item->getData( 'page_id' );
                $data['label'] = $item->getData( 'title' )." ( ID: ".$pageId." )";

                $res[] = $data;
            }
            $this->_options = $res;
        }

        return $this->_options;
    }
}

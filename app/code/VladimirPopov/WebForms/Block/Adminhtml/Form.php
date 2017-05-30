<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml;

class Form extends \Magento\Backend\Block\Widget\Container
{
    /**
     * @var string
     */
    protected $_template = 'webforms/form.phtml';

    protected function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'add_new_form',
            'label' => __('Add Form'),
            'class' => 'primary',
            'onclick' => 'setLocation(\''.$this->getUrl('webforms/form/new').'\')',
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        $import_url = $this->getUrl('*/*/import');

        $import_form = '
		<form action="' . $import_url . '" style="display:none" method="post" enctype="multipart/form-data">
		    <input name="form_key" type="hidden" value="' . $this->getFormKey() . '" />
		    <input type="file" id="import_form" name="import_form" accept="application/json" onchange="this.up().submit()"/>
        </form>';

        $this->addButton('import', array(
            'before_html' => $import_form,
            'label' => __('Import Form'),
            'onclick' => "$('import_form').click()"
        ));

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('VladimirPopov\WebForms\Block\Adminhtml\Form\Grid', 'form.grid')
        );
        return parent::_prepareLayout();
    }


    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }
}
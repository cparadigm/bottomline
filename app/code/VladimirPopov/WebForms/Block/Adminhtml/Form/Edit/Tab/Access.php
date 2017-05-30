<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Form\Edit\Tab;

class Access extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Access Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Access Settings');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Form */
        $model = $this->_coreRegistry->registry('webforms_form');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Vladimipopov_WebForms::form_save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout() . '_fieldset_element_renderer'
            )
        );
        $form->setDataObject($model);

        $form->setHtmlIdPrefix('form_');
        $form->setFieldNameSuffix('form');

        $fieldset = $form->addFieldset('customer_access', array(
            'legend' => __('Customer Access')
        ));

        $access_enable = $fieldset->addField('access_enable', 'select', array(
            'name' => 'access_enable',
            'label' => __('Limit customer access'),
            'note' => __('Limit access to the form for certain customer groups'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $access_groups = $fieldset->addField('access_groups', 'multiselect', array
        (
            'label' => __('Allowed customer groups'),
            'title' => __('Allowed customer groups'),
            'name' => 'access_groups',
            'required' => false,
            'note' => __('Allow form access for selected customer groups only'),
            'values' => $this->getGroupOptions(),
        ));

        $fieldset = $form->addFieldset('customer_dashboard', array(
            'legend' => __('Customer Dashboard')
        ));

        $dashboard_enable = $fieldset->addField('dashboard_enable', 'select', array(
            'name' => 'dashboard_enable',
            'label' => __('Add form to customer dashboard'),
            'note' => __('Add link to the form and submission results to customer dashboard menu'),
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ));

        $dashboard_groups = $fieldset->addField('dashboard_groups', 'multiselect', array
        (
            'label' => __('Customer groups'),
            'title' => __('Customer groups'),
            'name' => 'dashboard_groups',
            'required' => false,
            'note' => __('Add form to dashboard for selected customer groups only'),
            'values' => $this->getGroupOptions(),
        ));

//        $this->setChild('form_after', $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence', 'form_access_dependence')
//            ->addFieldMap($access_enable->getHtmlId(), $access_enable->getName())
//            ->addFieldMap($access_groups->getHtmlId(), $access_groups->getName())
//            ->addFieldMap($dashboard_enable->getHtmlId(), $dashboard_enable->getName())
//            ->addFieldMap($dashboard_groups->getHtmlId(), $dashboard_groups->getName())
//            ->addFieldDependence(
//                $access_groups->getName(),
//                $access_enable->getName(),
//                1
//            )
//            ->addFieldDependence(
//                $dashboard_groups->getName(),
//                $dashboard_enable->getName(),
//                1
//            )
//        );

        $this->_eventManager->dispatch('adminhtml_webforms_form_edit_tab_access_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getGroupOptions()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $group_options = $objectManager->get('Magento\Customer\Model\ResourceModel\Group\Collection')->toOptionArray();
        $options = [];
        foreach ($group_options as $group) {
            if ($group['value'] > 0) $options[] = $group;
        }
        return $options;
    }
}
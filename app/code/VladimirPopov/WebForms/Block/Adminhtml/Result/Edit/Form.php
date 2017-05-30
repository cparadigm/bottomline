<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Result\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $_localeResolver;

    protected $_sourceCountry;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Directory\Model\Config\Source\Country $sourceCountry,
        array $data = []
    )
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_localeResolver = $localeResolver;
        $this->_sourceCountry = $sourceCountry;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('result_form');
        $this->setTitle(__('Result Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('webforms_result');
        $modelForm = $this->_coreRegistry->registry('webforms_form');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', ['_current' => true]),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ]]
        );

        $form->setFieldNameSuffix('result');

        if ($model->getId())
            $fieldset = $form->addFieldset('result_info', array('legend' => __('Result # %1', $model->getId())));
        else
            $fieldset = $form->addFieldset('result_info', array('legend' => __('New Result')));

        $customer_id = $model->getCustomerId();
        $model->setCustomer($customer_id);
        $customer_ip = long2ip($model->getData('customer_ip'));

        $model->addData(array(
            'info_customer_ip' => $customer_ip,
            'info_created_time' => $this->_localeDate->formatDate($model->getCreatedTime(), \IntlDateFormatter::MEDIUM, true),
            'info_webform_name' => $modelForm->getName(),
        ));


        $fieldset->addField('info_webform_name', 'link', array(
            'id' => 'info_webform_name',
            'class' => 'control-value special',
            'href' => $this->getUrl('*/form/edit', array('id' => $modelForm->getId())),
            'label' => __('Web-form'),
        ));

        if ($model->getId())
            $fieldset->addField('info_created_time', 'label', array(
                'id' => 'info_created_time',
                'bold' => true,
                'label' => __('Result Date'),
            ));

        $fieldset->addType('customer', 'VladimirPopov\WebForms\Block\Adminhtml\Result\Element\Customer');

        $fieldset->addField(
            'customer', 'customer',
            array(
                'label' => __('Customer'),
                'name' => 'customer',
            )
        );

        $fieldset->addField(
            'store_id', 'select',
            array(
                'name' => 'store_id',
                'label' => __('Store View'),
                'values' => $this->_systemStore->getStoreValuesForForm(false, false),
                'required' => true,
            )
        );

        if ($model->getId())
            $fieldset->addField('info_customer_ip', 'label', array(
                'id' => 'info_customer_ip',
                'bold' => true,
                'label' => __('Sent from IP'),
            ));

        $wysiwygConfig = $this->_wysiwygConfig->getConfig();

        $fields_to_fieldsets = $modelForm->getFieldsToFieldsets(true);

        foreach ($fields_to_fieldsets as $fs_id => $fs_data) {
            $legend = "";
            if (!empty($fs_data['name'])) $legend = $fs_data['name'];

            // check logic visibility
            $fieldset = $form->addFieldset('fs_' . $fs_id, array(
                'legend' => $legend,
                'fieldset_container_id' => 'fieldset_' . $fs_id . '_container'
            ));

            foreach ($fs_data['fields'] as $field) {
                $type = 'text';
                $config = array
                (
                    'name' => 'field[' . $field->getId() . ']',
                    'label' => $field->getName(),
                    'container_id' => 'field_' . $field->getId() . '_container',
                    'required' => $field->getRequired()
                );

                $dateFormatIso = $this->_localeDate->getDateFormat(\IntlDateFormatter::MEDIUM);
                $timeFormatIso = $this->_localeDate->getTimeFormat(\IntlDateFormatter::SHORT);

                switch ($field->getType()) {
                    case 'textarea':
                    case 'hidden':
                        $type = 'textarea';
                        break;

                    case 'wysiwyg':
                        $type = 'editor';
                        $config['config'] = $wysiwygConfig;
                        break;

                    case 'date':
                    case 'date/dob':
                        $type = 'date';
                        $config['date_format'] = $dateFormatIso;
                        break;

                    case 'datetime':
                        $type = 'date';
                        $config['time'] = true;
                        $config['date_format'] = $dateFormatIso;
                        $config['time_format'] = $timeFormatIso;
                        break;

                    case 'select/radio':
                        $type = 'select';
                        $config['required'] = false;
                        $config['values'] = $field->getOptionsArray();
                        break;

                    case 'select/checkbox':
                        $type = 'checkboxes';
                        $value = explode("\n", $model->getData('field_' . $field->getId()));
                        $model->setData('field_' . $field->getId(), $value);
                        $config['options'] = $field->getSelectOptions();
                        $config['name'] = 'field[' . $field->getId() . '][]';
                        break;

                    case 'select':
                        $type = 'select';
                        $config['options'] = $field->getSelectOptions();
                        break;

                    case 'subscribe':
                        $type = 'select';
                        $config['options'] = ['1' => __('Yes'), '0' => __('No')];
                        break;

                    case 'select/contact':
                        $type = 'select';
                        $config['options'] = $field->getSelectOptions(false);
                        break;

                    case 'stars':
                        $type = 'select';
                        $config['options'] = $field->getStarsOptions();
                        break;

                    case 'file':
                        $type = 'file';
                        $config['field_id'] = $field->getId();
                        $config['result_id'] = $model->getId();
                        $config['url'] = $model->getFilePath($field->getId());
                        $config['name'] = 'file_' . $field->getId();
                        break;

                    case 'image':
                        $type = 'image';
                        $config['field_id'] = $field->getId();
                        $config['result_id'] = $model->getId();
                        $config['url'] = $model->getFilePath($field->getId());
                        $config['name'] = 'file_' . $field->getId();
                        break;

                    case 'html':
                        $type = 'label';
                        $config['label'] = false;
                        $config['after_element_html'] = $field->getValue('html');
                        break;

                    case 'country':
                        $type = 'select';
                        $config['values'] = $this->_sourceCountry->toOptionArray();
                        break;
                }
                $config['type'] = $type;
                $config = new \Magento\Framework\DataObject($config);
                $fieldset->addType('image', 'VladimirPopov\WebForms\Block\Adminhtml\Result\Element\Image');
                $fieldset->addType('file', 'VladimirPopov\WebForms\Block\Adminhtml\Result\Element\File');

                $this->_eventManager->dispatch('webforms_block_adminhtml_results_edit_form_prepare_layout_field', array('form' => $form, 'fieldset' => $fieldset, 'field' => $field, 'config' => $config));
                $fieldset->addField('field_' . $field->getId(), $config->getData('type'), $config->getData());
            }
        }

        $form->setValues($model->getData());

        $form->addField('result_id', 'hidden', array
        (
            'name' => 'result_id',
            'value' => $model->getId(),
        ));

        $form->addField('webform_id', 'hidden', array
        (
            'name' => 'webform_id',
            'value' => $modelForm->getId(),
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
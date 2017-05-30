<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Field\Edit\Tab;

class Information extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    )
    {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \VladimirPopov\WebForms\Model\Field */
        $model = $this->_coreRegistry->registry('webforms_field');

        /* @var $model \VladimirPopov\WebForms\Model\Form */
        $modelForm = $this->_coreRegistry->registry('webforms_form');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('VladimirPopov_WebForms::manage_forms')) {
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

        $form->setHtmlIdPrefix('field_');
        $form->setFieldNameSuffix('field');

        if ($model->getId())
            $form->addField('id', 'hidden', [
                'name' => 'id',
            ]);

        $form->addField('webform_id', 'hidden', [
            'name' => 'webform_id',
        ]);

        $fieldset = $form->addFieldset('webforms_form', [
            'legend' => __('Information')
        ]);

        $fieldset->addField('name', 'text', [
            'label' => __('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name'
        ]);

        $type = $fieldset->addField('type', 'select', [
            'label' => __('Type'),
            'title' => __('Type'),
            'name' => 'type',
            'required' => false,
            'options' => $model->getFieldTypes(),
        ]);

        $fieldset->addField('code', 'text', [
            'label' => __('Code'),
            'name' => 'code',
            'note' => __('Code is used to help identify this field in scripts'),
        ]);

        $result_label = $fieldset->addField('result_label', 'text', [
            'label' => __('Result label'),
            'required' => false,
            'name' => 'result_label',
            'note' => __('Result label will be used on results page. Use it to shorten long question fields')
        ]);

        $hint = $fieldset->addField('hint', 'text', [
            'label' => __('Hint'),
            'required' => false,
            'name' => 'hint',
            'note' => __('Hint message will appear in the input and disappear on the focus'),
        ]);

        $hint_email = $fieldset->addField('hint_email', 'text', [
            'label' => __('Hint'),
            'required' => false,
            'name' => 'hint_email',
            'note' => __('Hint message will appear in the input and disappear on the focus'),
        ]);

        $hint_url = $fieldset->addField('hint_url', 'text', [
            'label' => __('Hint'),
            'required' => false,
            'name' => 'hint_url',
            'note' => __('Hint message will appear in the input and disappear on the focus'),
        ]);

        $hint_textarea = $fieldset->addField('hint_textarea', 'text', [
            'label' => __('Hint'),
            'required' => false,
            'name' => 'hint_textarea',
            'note' => __('Hint message will appear in the input and disappear on the focus'),
        ]);

        $comment = $fieldset->addField('comment', 'textarea', [
            'label' => __('Comment'),
            'required' => false,
            'name' => 'comment',
            'style' => 'height:10em;',
            'note' => __('This text will appear under the input field.<br>Use <i>{{tooltip}}text{{/tooltip}}</i> to add tooltip to field name.<br>Use <i>{{tooltip val=&quot;Option&quot;}}text{{/tooltip}}</i> to add tooltip to checkbox or radio label.'),
        ]);

        $fieldsetsOptions = $modelForm->getFieldsetsOptionsArray();
        if (count($fieldsetsOptions) > 1) {
            $fieldset->addField('fieldset_id', 'select', [
                'label' => __('Field set'),
                'title' => __('Field set'),
                'name' => 'fieldset_id',
                'required' => false,
                'options' => $fieldsetsOptions,
            ]);
        }

        $autocomplete_choices = $fieldset->addField('value_autocomplete_choices','textarea',[
            'label' => __('Auto-complete choices'),
            'required' => false,
            'name' => 'value[autocomplete_choices]',
            'note' => __('Drop-down list of auto-complete choices. Values should be separated with new line'),
        ]);

        $options = $fieldset->addField('value_options', 'textarea', [
            'label' => __('Options'),
            'required' => false,
            'name' => 'value[options]',
            'note' => __('Select values should be separated with new line<br>Use <i>^Option Text</i> to check default<br>Use <i>Option Text {{null}}</i> to create option without value</i><br>Use <i>Option Text {{val VALUE}}</i> to set different option value<br>Use <i>Option Text {{disabled}}</i> to create disabled option'),
        ]);

        $options_radio = $fieldset->addField('value_options_radio', 'textarea', [
            'label' => __('Options'),
            'required' => false,
            'name' => 'value[options_radio]',
            'note' => __('Select values should be separated with new line<br>Use <i>^Option Text</i> to check default<br>Use <i>Option Text {{null}}</i> to create option without value</i><br>Use <i>Option Text {{val VALUE}}</i> to set different option value'),
        ]);

        $options_checkbox = $fieldset->addField('value_options_checkbox', 'textarea', [
            'label' => __('Options'),
            'required' => false,
            'name' => 'value[options_checkbox]',
            'note' => __('Select values should be separated with new line<br>Use <i>^Option Text</i> to check default<br>Use <i>Option Text {{null}}</i> to create option without value</i><br>Use <i>Option Text {{val VALUE}}</i> to set different option value'),
        ]);

        $options_contact = $fieldset->addField('value_options_contact', 'textarea', [
            'label' => __('Options'),
            'required' => false,
            'name' => 'value[options_contact]',
            'note' => __('Select values should be separated with new line<br>Use <i>^Option Text</i> to check default<br>Options format:<br><i>Site Admin &lt;admin@mysite.com&gt;<br>Sales &lt;sales@mysite.com&gt;</i>'),
        ]);

        $text_value = $fieldset->addField('value_text', 'text', [
            'label' => __('Field value'),
            'name' => 'value[text]',
            'note' => __('Following codes pre-fill data for registered customer:<br>{{email}} - customer e-mail address<br>{{firstname}} - first name of the customer<br>{{lastname}} - last name of the customer<br>{{company}} - billing profile company<br>{{city}} - billing profile city<br>{{street}} - billing profile street<br>{{country_id}} - billing profile country 2 symbol code<br>{{region}} - billing profile region<br>{{postcode}} - billing profile postcode<br>{{telephone}} - billing profile telephone<br>{{fax}} - billing profile fax')
        ]);

        $text_value_email = $fieldset->addField('value_text_email', 'text', [
            'label' => __('Field value'),
            'name' => 'value[text_email]',
            'note' => __('Following codes pre-fill data for registered customer:<br>{{email}} - customer e-mail address<br>{{firstname}} - first name of the customer<br>{{lastname}} - last name of the customer<br>{{company}} - billing profile company<br>{{city}} - billing profile city<br>{{street}} - billing profile street<br>{{country_id}} - billing profile country 2 symbol code<br>{{region}} - billing profile region<br>{{postcode}} - billing profile postcode<br>{{telephone}} - billing profile telephone<br>{{fax}} - billing profile fax')
        ]);

        $assign_customer_id_by_email = $fieldset->addField('value_assign_customer_id_by_email','select',[
            'label' => __('Assign Customer ID automatically'),
            'name' => 'value[assign_customer_id_by_email]',
            'note' => __('Assign Customer ID automatically if e-mail address matches customer account in the database'),
            'options'   => ['1' => __('Yes'), '0' => __('No')],
        ]);

        $text_value_url = $fieldset->addField('value_text_url', 'text', [
            'label' => __('Field value'),
            'name' => 'value[text_url]',
            'note' => __('Following codes pre-fill data for registered customer:<br>{{email}} - customer e-mail address<br>{{firstname}} - first name of the customer<br>{{lastname}} - last name of the customer<br>{{company}} - billing profile company<br>{{city}} - billing profile city<br>{{street}} - billing profile street<br>{{country_id}} - billing profile country 2 symbol code<br>{{region}} - billing profile region<br>{{postcode}} - billing profile postcode<br>{{telephone}} - billing profile telephone<br>{{fax}} - billing profile fax')
        ]);

        $textarea_value = $fieldset->addField('value_textarea', 'textarea', [
            'label' => __('Field value'),
            'name' => 'value[textarea]',
            'note' => __('Following codes pre-fill data for registered customer:<br>{{email}} - customer e-mail address<br>{{firstname}} - first name of the customer<br>{{lastname}} - last name of the customer<br>{{company}} - billing profile company<br>{{city}} - billing profile city<br>{{street}} - billing profile street<br>{{country_id}} - billing profile country 2 symbol code<br>{{region}} - billing profile region<br>{{postcode}} - billing profile postcode<br>{{telephone}} - billing profile telephone<br>{{fax}} - billing profile fax')
        ]);

        $number_min = $fieldset->addField('value_number_min', 'text', [
            'label' => __('Minimum value'),
            'name' => 'value[number_min]',
            'note' => __('Minimum integer value that can be entered'),
            'class' => 'validate-number'
        ]);

        $number_max = $fieldset->addField('value_number_max', 'text', [
            'label' => __('Maximum value'),
            'name' => 'value[number_max]',
            'note' => __('Maximum integer value that can be entered'),
            'class' => 'validate-number'
        ]);

        $stars_init = $fieldset->addField('value_stars_init', 'text', [
            'label' => __('Number of stars selected by default'),
            'note' => __('3 stars are selected by default'),
            'name' => 'value[stars_init]',
            'class' => 'validate-number'
        ]);

        $stars_max = $fieldset->addField('value_stars_max', 'text', [
            'label' => __('Total amount of stars'),
            'name' => 'value[stars_max]',
            'note' => __('5 stars are available by default'),
            'class' => 'validate-number'
        ]);

        $newsletter_label = $fieldset->addField('value_newsletter_label', 'text', [
            'label' => __('Newsletter subscription checkbox label'),
            'name' => 'value[newsletter_label]',
            'note' => __('Overwrite default text &quot;Sign Up for Newsletter&quot;<br>Use <i>^Sign Up for Newsletter</i> to check by default'),
        ]);

        $allowed_extensions = $fieldset->addField('value_allowed_extensions', 'textarea', [
            'label' => __('Allowed file extensions'),
            'name' => 'value[allowed_extensions]',
            'note' => __('Specify allowed file extensions separated by newline. Example:<br><i>doc<br>txt<br>pdf</i>')
        ]);

        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);

        $html_content = $fieldset->addField('value_html', 'textarea', [
            'label' => __('HTML content'),
            'name' => 'value[html]',
            'style' => 'height:10em;',
            'config' => $wysiwygConfig
        ]);

        $hidden_value = $fieldset->addField('value_hidden', 'textarea', [
            'label' => __('Hidden field value'),
            'name' => 'value[hidden]',
            'note' => __("You can use variables to store dynamic information. Example:<br><i>{{var product.sku}}<br>{{var category.name}}<br>{{var customer.email}}<br>{{var url}}</i>")
        ]);

        $image_resize = $fieldset->addField('value_image_resize', 'select', [
            'label' => __('Resize uploaded image'),
            'name' => 'value[image_resize]',
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ]);

        $image_resize_width = $fieldset->addField('value_image_resize_width', 'text', [
            'label' => __('Maximum width'),
            'name' => 'value[image_resize_width]',
            'class' => 'validate-number'
        ]);

        $image_resize_height = $fieldset->addField('value_image_resize_height', 'text', [
            'label' => __('Maximum height'),
            'name' => 'value[image_resize_height]',
            'class' => 'validate-number'
        ]);

        $fieldset->addField('email_subject', 'select', [
            'label' => __('Use field value as e-mail subject'),
            'title' => __('Use field value as e-mail subject'),
            'name' => 'email_subject',
            'note' => __('This field value will be used as a subject in notification e-mail'),
            'required' => false,
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ]);

        $required = $fieldset->addField('required', 'select', [
            'label' => __('Required'),
            'title' => __('Required'),
            'name' => 'required',
            'required' => false,
            'options' => ['1' => __('Yes'), '0' => __('No')],
        ]);

        $validation_advice = $fieldset->addField('validation_advice', 'text', [
            'label' => __('Custom validation advice'),
            'name' => 'validation_advice',
            'note' => __('Set custom text for the validation error message. If empty <b>&quot;This is a required field.&quot;</b> will be used'),
        ]);

        $fieldset->addField('position', 'text', [
            'label' => __('Position'),
            'required' => true,
            'name' => 'position',
            'note' => __('Field position in the form relative to field set'),
        ]);

        $fieldset->addField('is_active', 'select', [
            'label' => __('Status'),
            'title' => __('Status'),
            'name' => 'is_active',
            'note' => __('If assigned field set is not active the field won`t be displayed'),
            'required' => false,
            'options' => $model->getAvailableStatuses(),
        ]);



        $this->setChild('form_after', $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence', 'fields_information_dependence')
            ->addFieldMap($type->getHtmlId(), $type->getName())
            ->addFieldMap($required->getHtmlId(), $required->getName())
            ->addFieldMap($number_min->getHtmlId(), $number_min->getName())
            ->addFieldMap($number_max->getHtmlId(), $number_max->getName())
            ->addFieldMap($validation_advice->getHtmlId(), $validation_advice->getName())
            ->addFieldMap($text_value->getHtmlId(), $text_value->getName())
            ->addFieldMap($text_value_email->getHtmlId(), $text_value_email->getName())
            ->addFieldMap($text_value_url->getHtmlId(), $text_value_url->getName())
            ->addFieldMap($options->getHtmlId(), $options->getName())
            ->addFieldMap($options_radio->getHtmlId(), $options_radio->getName())
            ->addFieldMap($options_checkbox->getHtmlId(), $options_checkbox->getName())
            ->addFieldMap($options_contact->getHtmlId(), $options_contact->getName())
            ->addFieldMap($textarea_value->getHtmlId(), $textarea_value->getName())
            ->addFieldMap($newsletter_label->getHtmlId(), $newsletter_label->getName())
            ->addFieldMap($stars_init->getHtmlId(), $stars_init->getName())
            ->addFieldMap($stars_max->getHtmlId(), $stars_max->getName())
            ->addFieldMap($hint->getHtmlId(), $hint->getName())
            ->addFieldMap($hint_email->getHtmlId(), $hint_email->getName())
            ->addFieldMap($hint_url->getHtmlId(), $hint_url->getName())
            ->addFieldMap($hint_textarea->getHtmlId(), $hint_textarea->getName())
            ->addFieldMap($allowed_extensions->getHtmlId(), $allowed_extensions->getName())
            ->addFieldMap($html_content->getHtmlId(), $html_content->getName())
            ->addFieldMap($hidden_value->getHtmlId(), $hidden_value->getName())
            ->addFieldMap($image_resize->getHtmlId(), $image_resize->getName())
            ->addFieldMap($image_resize_width->getHtmlId(), $image_resize_width->getName())
            ->addFieldMap($image_resize_height->getHtmlId(), $image_resize_height->getName())
            ->addFieldMap($assign_customer_id_by_email->getHtmlId(), $assign_customer_id_by_email->getName())
            ->addFieldMap($autocomplete_choices->getHtmlId(), $autocomplete_choices->getName())
            ->addFieldDependence(
                $hint->getName(),
                $type->getName(),
                'text'
            )
            ->addFieldDependence(
                $hint_email->getName(),
                $type->getName(),
                'email'
            )
            ->addFieldDependence(
                $assign_customer_id_by_email->getName(),
                $type->getName(),
                'email'
            )
            ->addFieldDependence(
                $hint_url->getName(),
                $type->getName(),
                'url'
            )
            ->addFieldDependence(
                $hint_textarea->getName(),
                $type->getName(),
                'textarea'
            )
            ->addFieldDependence(
                $number_min->getName(),
                $type->getName(),
                'number'
            )
            ->addFieldDependence(
                $number_max->getName(),
                $type->getName(),
                'number'
            )
            ->addFieldDependence(
                $text_value->getName(),
                $type->getName(),
                'text'
            )
            ->addFieldDependence(
                $text_value_email->getName(),
                $type->getName(),
                'email'
            )
            ->addFieldDependence(
                $text_value_url->getName(),
                $type->getName(),
                'url'
            )
            ->addFieldDependence(
                $textarea_value->getName(),
                $type->getName(),
                'textarea'
            )
            ->addFieldDependence(
                $newsletter_label->getName(),
                $type->getName(),
                'subscribe'
            )
            ->addFieldDependence(
                $options->getName(),
                $type->getName(),
                'select'
            )
            ->addFieldDependence(
                $options_radio->getName(),
                $type->getName(),
                'select/radio'
            )
            ->addFieldDependence(
                $options_checkbox->getName(),
                $type->getName(),
                'select/checkbox'
            )
            ->addFieldDependence(
                $options_contact->getName(),
                $type->getName(),
                'select/contact'
            )
            ->addFieldDependence(
                $stars_init->getName(),
                $type->getName(),
                'stars'
            )
            ->addFieldDependence(
                $stars_max->getName(),
                $type->getName(),
                'stars'
            )
            ->addFieldDependence(
                $allowed_extensions->getName(),
                $type->getName(),
                'file'
            )
            ->addFieldDependence(
                $html_content->getName(),
                $type->getName(),
                'html'
            )
            ->addFieldDependence(
                $hidden_value->getName(),
                $type->getName(),
                'hidden'
            )
            ->addFieldDependence(
                $image_resize->getName(),
                $type->getName(),
                'image'
            )
            ->addFieldDependence(
                $image_resize_width->getName(),
                $type->getName(),
                'image'
            )
            ->addFieldDependence(
                $image_resize_height->getName(),
                $type->getName(),
                'image'
            )
            ->addFieldDependence(
                $validation_advice->getName(),
                $required->getName(),
                '1'
            )
            ->addFieldDependence(
                $autocomplete_choices->getName(),
                $type->getName(),
                'autocomplete'
            )

        );

        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
            $model->setData('position', $model->getResource()->getNextPosition($modelForm->getId()));
        }

        $this->_eventManager->dispatch('adminhtml_webforms_field_edit_tab_information_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Information');
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
}
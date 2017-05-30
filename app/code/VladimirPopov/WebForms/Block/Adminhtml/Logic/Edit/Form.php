<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Block\Adminhtml\Logic\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $_logicAction;

    protected $_logicCondition;

    protected $_logicAggregation;
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
        \Magento\Store\Model\System\Store $systemStore,
        \VladimirPopov\WebForms\Model\Logic\Action $logicAction,
        \VladimirPopov\WebForms\Model\Logic\Condition $logicCondition,
        \VladimirPopov\WebForms\Model\Logic\Aggregation $logicAggregation,
        array $data = []
    )
    {
        $this->_systemStore = $systemStore;
        $this->_logicAction = $logicAction;
        $this->_logicCondition = $logicCondition;
        $this->_logicAggregation = $logicAggregation;
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
        $this->setId('logic_form');
        $this->setTitle(__('Logic Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('webforms_logic');
        $modelField = $this->_coreRegistry->registry('webforms_field');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getUrl('*/*/save',['store' => $this->getRequest()->getParam('store')]), 'method' => 'post']]
        );


        $form->setDataObject($model);

        $form->setHtmlIdPrefix('logic_');
        $form->setFieldNameSuffix('logic');
        $form->setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'VladimirPopov\WebForms\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout() . '_fieldset_element_renderer'
            )
        );
        if ($model->getId())
            $form->addField('id', 'hidden', array(
                'name' => 'id',
            ));

        $fieldset = $form->addFieldset('fieldset_information', array(
            'legend' => __('Logic Rule')
        ));

        $fieldset->addField('logic_condition', 'select', array(
            'label' => __('Condition'),
            'name' => 'logic_condition',
            'options' => $this->_logicCondition->getOptions(),
        ));

        $fieldset->addField('value', 'multiselect', array(
            'label' => __('Trigger value(s)'),
            'required' => true,
            'name' => 'value',
            'note' => __('Select one or multiple trigger values.<br>Please, configure for each locale <b>Store View</b>.'),
            'values' => $modelField->getOptionsArray()
        ));

        $fieldset->addField('action', 'select', array(
            'label' => __('Action'),
            'name' => 'action',
            'options' => $this->_logicAction->getOptions(),
            'note' => __('Action to perform with target elements'),
        ));

        $fieldset->addField('target', 'multiselect', array(
            'label' => __('Target element(s)'),
            'required' => true,
            'name' => 'target',
            'note' => __('Select one or multiple target elements'),
            'values' => $modelField->getLogicTargetOptionsArray()
        ));

        if ($modelField->getType() == 'select/checkbox') {
            $fieldset->addField('aggregation', 'select', array(
                'label' => __('Logic aggregation'),
                'name' => 'aggregation',
                'options' => $this->_logicAggregation->getOptions()
            ));
        }

        $fieldset->addField('is_active', 'select', array(
            'label' => __('Status'),
            'title' => __('Status'),
            'name' => 'is_active',
            'options' => $model->getAvailableStatuses(),
        ));

        $form->addField('field_id', 'hidden', array(
            'name' => 'field_id',
        ));

        if (!$model->getId()) {
            $model->setData('is_active', '0');
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
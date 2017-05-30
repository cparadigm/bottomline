<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;


class Field extends AbstractModel implements IdentityInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected $img_regex = '/{{img ([\w\/\.-]+)}}/';
    protected $val_regex = '/{{val (.*?)}}/';
    protected $contact_regex = '/ *\<[^\>]+\> *$/';
    protected $php_regex = '/<\?php(.*?)\?>/';
    protected $tooltip_regex = "/{{tooltip}}(.*?){{\\/tooltip}}/si";
    protected $tooltip_option_regex = "/{{tooltip\s*val=\"(.*?)\"}}(.*?){{\\/tooltip}}/si";
    protected $tooltip_clean_regex = "/{{tooltip(.*?)}}(.*?){{\\/tooltip}}/si";

    /**
     * Form cache tag
     */
    const CACHE_TAG = 'webforms_field';

    /**
     * @var string
     */
    protected $_cacheTag = 'webforms_field';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'webforms_field';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */

    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    protected $_session;

    protected $_urlBuilder;

    protected $_formFactory;

    protected $_logicFactory;

    protected $_fieldFactory;

    protected $_countryCollectionFactory;

    protected $random;

    protected $_webform;

    protected $_request;

    protected $_filterProvider;

    protected $_layout;

    protected $_storeManager;

    public function __construct(
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Framework\Math\Random $random,
        \VladimirPopov\WebForms\Model\FieldFactory $fieldFactory,
        \VladimirPopov\WebForms\Model\LogicFactory $logicFactory,
        \VladimirPopov\WebForms\Model\FormFactory $formFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Url $urlBuilder,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\View\Layout $layout,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \VladimirPopov\WebForms\Model\StoreFactory $storeFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_session = $sessionFactory->create();
        $this->_request = $request;
        $this->_scopeConfig = $scopeConfig;
        $this->_localeDate = $localeDate;
        $this->_urlBuilder = $urlBuilder;
        $this->_filterProvider = $filterProvider;
        $this->_layout = $layout;
        $this->_formFactory = $formFactory;
        $this->_logicFactory = $logicFactory;
        $this->_fieldFactory = $fieldFactory;
        $this->_countryCollectionFactory = $countryCollectionFactory;
        $this->random = $random;
        $this->_storeManager = $storeManager;
        parent::__construct($storeFactory, $context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('VladimirPopov\WebForms\Model\ResourceModel\Field');
    }

    public function getRequest()
    {
        return $this->_request;
    }

    public function getWebform()
    {
        if (!$this->_webform) {
            /** @var \VladimirPopov\WebForms\Model\Form $webform */
            $webform = $this->_formFactory->create()->setStoreId($this->getStoreId())->load($this->getWebformId());
            $this->_webform = $webform;
        }
        return $this->_webform;
    }

    /**
     * Prepare form's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData('id');
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getId();
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool)$this->getData('is_active');
    }

    public function getFieldTypes()
    {
        $types = new \Magento\Framework\DataObject(array(
            "text" => __('Text'),
            "email" => __('Text / E-mail'),
            "number" => __('Text / Number'),
            "url" => __('Text / URL'),
            "password" => __('Text / Password'),
            "autocomplete" => __('Text / Auto-complete'),
            "textarea" => __('Textarea'),
            "wysiwyg" => __('HTML Editor'),
            "select" => __('Select'),
            "select/radio" => __('Select / Radio'),
            "select/checkbox" => __('Select / Checkbox'),
            "select/contact" => __('Select / Contact'),
            "country" => __('Select / Country'),
            "subscribe" => __('Newsletter Subscription / Checkbox'),
            "date" => __('Date'),
            "datetime" => __('Date / Time'),
            "date/dob" => __('Date of Birth'),
            "stars" => __('Star Rating'),
            "file" => __('File Upload'),
            "image" => __('Image Upload'),
            "html" => __('HTML Block'),
        ));

        $types->setData('hidden', __('Hidden'));

        // add more field types
        $this->_eventManager->dispatch('webforms_fields_types', array('types' => $types));

        return $types->getData();

    }

    public function getSizeTypes()
    {
        $types = new \Magento\Framework\DataObject(array(
            "standard" => __('Standard'),
            "wide" => __('Wide'),
        ));

        // add more size types
        $this->_eventManager->dispatch('webforms_fields_size_types', array('types' => $types));

        return $types->getData();

    }

    public function getDisplayOptions()
    {
        $options = array(
            array('value' => 'on', 'label' => __('On')),
            array('value' => 'off', 'label' => __('Off')),
            array('value' => 'value', 'label' => __('Value only')),
        );
        return $options;
    }

    public function getComment()
    {
        $comment = $this->getData('comment');
        return trim(preg_replace($this->tooltip_clean_regex, "", $comment));
    }

    public function getTooltip($option = false)
    {
        $matches = array();
        $pattern = $this->tooltip_regex;
        $comment = $this->getData('comment');

        if ($option) {
            $pattern = $this->tooltip_option_regex;
            preg_match_all($pattern, $comment, $matches);
            if (!empty($matches[1]))
                foreach ($matches[1] as $i => $match) {
                    if (trim($match) == trim($option))
                        return $matches[2][$i];
                }
            return false;
        }

        preg_match($pattern, $comment, $matches);

        if (!empty($matches[1]))
            return trim($matches[1]);

        return false;
    }

    public function getName()
    {
        if ($this->_scopeConfig->getValue('webforms/general/use_translation')) {
            return __($this->getData('name'));
        }

        return $this->getData('name');
    }

    public function getSelectOptions($clean = true)
    {
        $field_value = $this->getValue();
        $options = explode("\n", $field_value['options']);
        $options = array_map('trim', $options);
        $select_options = array();
        foreach ($options as $o) {
            if ($this->getType() == 'select/contact') {
                if ($clean) {
                    $contact = $this->getContactArray($o);
                    $o = $contact['name'];
                }
            }
            $value = $this->getCheckedOptionValue($o);
            $label = $value;
            $matches = array();
            preg_match($this->val_regex, $value, $matches);
            if (isset($matches[1])) {
                $value = trim($matches[1]);
                $label = preg_replace($this->val_regex, "", $label);
            }
            $select_options[$value] = trim($label);
        }
        return $select_options;
    }

    public function getResultsOptions()
    {
        $query = $this->getResource()->getConnection()
            ->select('value')
            ->from($this->getResource()->getTable('webforms/results_values'), array('value'))
            ->where('field_id = ' . $this->getId())
            ->order('value asc')
            ->distinct();
        $results = $this->getResource()->getConnection()->fetchAll($query);
        $options = array();
        foreach ($results as $result) {
            $options[$result['value']] = $result['value'];
        }
        return $options;
    }

    public function getAllowedExtensions()
    {
        if ($this->getType() == 'image')
            return array('jpg', 'jpeg', 'gif', 'png');
        if ($this->getType() == 'file') {
            $allowed_extensions = explode("\n", trim($this->getValue('allowed_extensions')));
            $allowed_extensions = array_map('trim', $allowed_extensions);
            $allowed_extensions = array_map('strtolower', $allowed_extensions);
            $filtered_result = array();
            foreach ($allowed_extensions as $ext) {
                if (strlen($ext) > 0) {
                    $filtered_result[] = $ext;
                }
            }
            return $filtered_result;
        }
        return;
    }

    public function getRestrictedExtensions()
    {
        return array('php', 'pl', 'py', 'jsp', 'asp', 'htm', 'html', 'js', 'sh', 'shtml', 'cgi', 'com', 'exe', 'bat', 'cmd', 'vbs', 'vbe', 'jse', 'wsf', 'wsh', 'psc1');
    }

    public function getStarsCount()
    {
        //return default value
        $field_value = $this->getData('value');
        $value = 5;
        if (!empty($field_value['stars_max']))
            $value = $field_value['stars_max'];
        return intval($value);
    }

    public function getStarsInit()
    {
        //return default value
        $field_value = $this->getData('value');
        $value = ceil(intval($this->getStarsCount()) / 2);
        if (!empty($field_value['stars_init']))
            $value = $field_value['stars_init'];
        return intval($value);
    }

    public function getStarsOptions()
    {
        $count = $this->getStarsCount();
        $options = array();
        for ($i = 0; $i <= $count; $i++) {
            $options[$i] = $i;
        }
        return $options;
    }

    public function getDateType()
    {
        $type = \IntlDateFormatter::SHORT;
        return $type;
    }

    public function getDateFormat()
    {
        $format = $this->_localeDate->getDateFormat($this->getDateType());
        if ($this->getType() == 'datetime') {
            $format = $this->_localeDate->getDateTimeFormat($this->getDateType());
        }
        return $format;
    }

    /**
     * Zend Date To local date according Map array
     *
     * @var array
     */
    private static $_convertZendToStrftimeDate = array(
        'yyyy-MM-ddTHH:mm:ssZZZZ' => '%c',
        'EEEE' => '%A',
        'EEE' => '%a',
        'D' => '%j',
        'MMMM' => '%B',
        'MMM' => '%b',
        'MM' => '%m',
        'M' => '%m',
        'dd' => '%d',
        'd' => '%e',
        'yyyy' => '%Y',
        'yy' => '%Y',
        'y' => '%Y'
    );

    /**
     * Zend Date To local time according Map array
     *
     * @var array
     */
    private static $_convertZendToStrftimeTime = array(
        'a' => '%p',
        'hh' => '%I',
        'h' => '%I',
        'HH' => '%H',
        'H' => '%H',
        'mm' => '%M',
        'ss' => '%S',
        'z' => '%Z',
        'v' => '%Z'
    );

    /**
     * Convert Zend Date format to local time/date according format
     *
     * @param string $value
     * @param boolean $convertDate
     * @param boolean $convertTime
     * @return string
     */
    public static function convertZendToStrftime($value, $convertDate = true, $convertTime = true)
    {
        if ($convertTime) {
            $value = self::_convert($value, self::$_convertZendToStrftimeTime);
        }
        if ($convertDate) {
            $value = self::_convert($value, self::$_convertZendToStrftimeDate);
        }
        return $value;
    }

    /**
     * Convert value by dictionary
     *
     * @param string $value
     * @param array $dictionary
     * @return string
     */
    protected static function _convert($value, $dictionary)
    {
        foreach ($dictionary as $search => $replace) {
            $value = preg_replace('/(^|[^%])' . $search . '/', '$1' . $replace, $value);
        }
        return $value;
    }

    public function getDateStrFormat()
    {
        $str_format = self::convertZendToStrftime($this->getDateFormat(), true, true);
        return $str_format;
    }

    public function getDbDateFormat()
    {
        $format = "Y-m-d";
        if ($this->getType() == 'datetime') {
            $format = "Y-m-d H:i:s";
        }
        return $format;
    }

    public function formatDate($value)
    {
        if (strlen($value) > 0) {
            $format = $this->getDateStrFormat();
            if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
                $format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
            }
            return strftime($format, strtotime($value));
        }
        return;
    }

    public function isCheckedOption($value)
    {
        $customer_value = $this->getData('customer_value');
        if ($customer_value) {
            $customer_values_array = explode("\n", $customer_value);
            foreach ($customer_values_array as $val) {
                $realVal = $this->getRealCheckedOptionValue($value);
                if (trim($val) == $realVal) {
                    return true;
                }
                $realVal = trim(preg_replace($this->contact_regex, '', $realVal));
                if (trim($val) == $realVal) {
                    return true;
                }
            }
            return false;
        }
        if (substr($value, 0, 1) == '^')
            return true;
        return false;
    }

    public function isNullOption($value)
    {
        if (substr($value, 0, 2) == '^^')
            return true;
        if (stristr($value, '{{null}}'))
            return true;
        return false;
    }

    public function isDisabledOption($value)
    {
        if (stristr($value, '{{disabled}}'))
            return true;
        return false;
    }

    public function getCheckedOptionValue($value)
    {
        $value = preg_replace($this->img_regex, "", $value);
        $value = str_replace('{{null}}', '', $value);
        $value = str_replace('{{disabled}}', '', $value);

        if ($this->isNullOption($value) && substr($value, 0, 2) == '^^')
            return trim(substr($value, 2));
        if (substr($value, 0, 1) == '^')
            return trim(substr($value, 1));
        return trim($value);
    }

    public function getRealCheckedOptionValue($value)
    {
        $value = preg_replace($this->img_regex, "", $value);
        $matches = array();
        preg_match($this->val_regex, $value, $matches);
        if (!empty($matches[1])) {
            $value = trim($matches[1]);
        }

        if ($this->isNullOption($value))
            return trim(substr($value, 2));
        if (substr($value, 0, 1) == '^')
            return trim(substr($value, 1));
        return trim($value);
    }

    public function getOptionsArray()
    {
        $options = array();
        $field_value = $this->getValue('options');
        $values = [];
        if (!empty($field_value))
            $values = explode("\n", $field_value);
        foreach ($values as $val) {
            $image_src = false;

            $matches = array();
            preg_match($this->img_regex, $val, $matches);
            if (!empty($matches[1])) {
                $image_src = $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $matches[1];
            }

            if (strlen(trim($val)) > 0) {
                $value = $this->getCheckedOptionValue($val);
                $label = $value;

                if ($this->_scopeConfig->getValue('webforms/general/use_translation')) $label = __($value);

                $matches = array();
                preg_match($this->val_regex, $value, $matches);
                if (!empty($matches[1])) {
                    $value = trim($matches[1]);
                }

                $options[] = array(
                    'value' => @$this->getFilter()->filter($value),
                    'label' => trim(@$this->getFilter()->filter($label)),
                    'null' => $this->isNullOption($val),
                    'checked' => $this->isCheckedOption($val),
                    'disabled' => $this->isDisabledOption($val),
                    'image_src' => $image_src,
                );
            }
        }
        return $options;
    }

    public function getContactArray($value)
    {
        preg_match('/(\w.+) <([^<]+?)>/', $value, $matches);
        if (!empty($matches[1]) && !empty($matches[2]))
            return array("name" => trim($matches[1]), "email" => trim($matches[2]));
        return array("name" => trim($value), "email" => "");
    }

    public function getContactValueById($id)
    {
        $options = $this->getOptionsArray();
        if (!empty($options[$id]['value']))
            return $options[$id]['value'];
        return false;
    }

    public function getHiddenFieldValue()
    {
        if ($this->getConfig('field_value')) return $this->getConfig('field_value');

        $result = $this->getData('result');
        $customer_value = $result ? $result->getData('field_' . $this->getId()) : false;
        if ($customer_value) return $customer_value;

        $field_value = $this->getValue();

        $filter = $this->_filterProvider->getPageFilter();
        $filter->setVariables(array(
            'product' => $this->registry('product'),
            'category' => $this->registry('category'),
            'customer' => $this->getCustomer(),
            'core_session' => $this->_session,
            'customer_session' => $this->_session,
            'url' => $this->_storeManager->getStore()->getCurrentUrl(false)
        ));

        $this->_eventManager->dispatch('webforms_fields_hidden_value', ['field' => $this, 'filter' => $filter]);

        return trim($filter->filter($field_value['hidden']));
    }

    public function getFilter()
    {
        $filter = new \Magento\Framework\Filter\Template\Simple;

        $customer = $this->getCustomer();
        if ($customer) {
            if ($customer->getDefaultBillingAddress()) {
                foreach ($customer->getDefaultBillingAddress()->getData() as $key => $value) {
                    $filter->setData($key, $value);
                    if ($key == 'street') {
                        $streetArray = explode("\n", $value);
                        for ($i = 0; $i < count($streetArray); $i++) {
                            $filter->setData('street_' . ($i + 1), $streetArray[$i]);
                        }
                    }
                }
            }

            $customer_data = $customer->getData();
            foreach ($customer_data as $key => $value) {
                $filter->setData($key, $value);
            }
        }

        return $filter;
    }

    public function toHtml()
    {
        $filter = $this->getFilter();

        // apply custom filter
        $this->_eventManager->dispatch('webforms_fields_tohtml_filter', array('filter' => $filter));

        $field_id = "field" . $this->getUid() . $this->getId();
        $field_name = "field[" . $this->getId() . "]";
        $field_value = @$filter->filter($this->getValue());
        if (is_array($field_value))
            $field_value = array_map('trim', $field_value);
        if (is_string($field_value))
            $field_value = trim($field_value);
        $result = $this->getData('result');
        $customer_value = $result ? $result->getData('field_' . $this->getId()) : false;
        // set values from URL parameter
        if ($this->getWebform()->getAcceptUrlParameters()) {
            $request_value = trim(strval($this->getRequest()->getParam($this->getCode())));
            if ($request_value) $customer_value = $request_value;
        }

        $this->setData('customer_value', $customer_value);
        $field_type = $this->getType();
        $field_class = "input-text";
        $field_style = "";

        if ($field_type == 'file' || $field_type == 'image') {
            $field_class = "input-file";
        }
        if ($this->getRequired())
            $field_class .= " required-entry";
        if ($field_type == "email")
            $field_class .= " validate-email";
        if ($field_type == "number")
            $field_class .= " validate-number";

        if (!empty($field_value['number_min']) || (!empty($field_value['number_min']) && $field_value['number_min'] == '0')) {
            $field_class .= ' validate-field-number-min-' . $this->getId();
        }
        if (!empty($field_value['number_max']) || (!empty($field_value['number_max']) && $field_value['number_max'] == '0')) {
            $field_class .= ' validate-field-number-max-' . $this->getId();
        }

        if ($field_type == "url")
            $field_class .= " validate-url";
        if ($this->getCssClass()) {
            $field_class .= ' ' . $this->getCssClass();
        }
        if ($this->getData('validate_length_min') || $this->getData('validate_length_max')) {
            $field_class .= ' validate-length';
        }
        if ($this->getData('validate_length_min')) {
            $field_class .= ' minimum-length-' . $this->getData('validate_length_min');
        }
        if ($this->getData('validate_length_max')) {
            $field_class .= ' maximum-length-' . $this->getData('validate_length_max');
        }
        if ($this->getData('validate_regex')) {
            $field_class .= ' validate-field-' . $this->getId();
        }
        if ($this->getRequired() && $this->getHint()) {
            $field_class .= ' validate-field-hint-' . $this->getId();
        }
        if ($this->getCssStyle()) {
            $field_style = $this->getCssStyle();
        }
        $tinyMCE = false;
        $showTime = false;
        $calendar = false;
        $config = array(
            'field_id' => $field_id,
            'field_name' => $field_name,
            'field_class' => $field_class,
            'field_style' => $field_style,
            'result' => $result,
            'show_time' => 'false',
            'customer_value' => $customer_value,
            'template' => 'text.phtml'
        );
        if ($customer_value)
            $config['field_value'] = $customer_value;

        $block_type = 'VladimirPopov\WebForms\Block\Field\AbstractField';
        switch ($field_type) {
            case 'text':
                if (!$customer_value) empty($field_value['text']) ? $config['field_value'] = '' : $config['field_value'] = $field_value['text'];
                break;
            case 'email':
                if (!$customer_value) empty($field_value['text_email']) ? $config['field_value'] = '' : $config['field_value'] = $field_value['text_email'];
                break;
            case 'number':
                empty($field_value['number_min']) ? $config['min'] = false : $config['min'] = $field_value['number_min'];
                empty($field_value['number_max']) ? $config['max'] = false : $config['max'] = $field_value['number_max'];
                if (!empty($field_value['number_min'])) $field_value['number_min'] == '0' ? $config['min'] = 0 : $config['min'] = $field_value['number_min'];
                if (!empty($field_value['number_max'])) $field_value['number_max'] == '0' ? $config['max'] = 0 : $config['max'] = $field_value['number_max'];
                $config['template'] = 'number.phtml';
                break;
            case 'password':
                $config['template'] = 'password.phtml';
                break;
            case 'autocomplete':
                $config['choices'] = explode("\n", $field_value['autocomplete_choices']);
                $config['template'] = 'auto-complete.phtml';
                break;
            case 'textarea':
                if (!$customer_value) empty($field_value['textarea']) ? $config['field_value'] = '' : $config['field_value'] = $field_value['textarea'];
                $config['template'] = 'textarea.phtml';
                break;
            case 'wysiwyg':
                $tinyMCE = true;
                $config['template'] = 'wysiwyg.phtml';
                break;
            case 'select':
                $config['field_options'] = $this->getOptionsArray();
                $config['template'] = 'select.phtml';
                break;
            case 'select/contact':
                $config['field_options'] = $this->getOptionsArray();
                $config['template'] = 'select_contact.phtml';
                break;
            case 'select/radio':
                $config['field_class'] = $this->getCssClass();
                $config['field_options'] = $this->getOptionsArray();
                $config['template'] = 'select_radio.phtml';
                break;
            case 'select/checkbox':
                $config['field_class'] = $this->getCssClass();
                $config['field_options'] = $this->getOptionsArray();
                $config['template'] = 'select_checkbox.phtml';
                break;
            case 'subscribe':
                $config['field_class'] = $this->getCssClass();
                $config['label'] = $field_value['newsletter_label'];
                $config['template'] = 'subscribe.phtml';
                break;
            case 'stars':
                $config['field_options'] = $this->getOptionsArray();
                $config['template'] = 'stars.phtml';
                break;
            case 'image':
            case 'file':
                $config['field_name'] = 'file_' . $this->getId();
                $config['template'] = 'file.phtml';
                break;
            case 'html':
                $config['field_value'] = $this->_filterProvider->getBlockFilter()->filter($this->getValue('html'));
                $config['template'] = 'html.phtml';
                break;
            case 'datetime':
                $config['show_time'] = 'true';
                $showTime = true;
            case 'date':
                $calendar = true;
                $config['accdc'] = $this->_scopeConfig->getValue('webforms/accessibility/accdc_calendar');
                if ($customer_value) {
                    // format customer value
                    $config['customer_value'] = $this->_localeDate->formatDateTime($customer_value, $this->getDateType(), $showTime);
                }
                $config['template'] = 'date.phtml';
                break;
            case 'date/dob':
                $block_type = 'VladimirPopov\WebForms\Block\Field\Dob';
                if ($customer_value) {
                    // set dob
                    $config['time'] = strtotime($customer_value);
                }
                $config['template'] = 'dob.phtml';
                break;
            case 'hidden':
                $config['template'] = 'hidden.phtml';
                break;
            case 'country':
                if (empty($config['field_value']) && !$config['customer_value']) {
                    $customer = $this->getCustomer();
                    if ($customer) {
                        $address = $customer->getDefaultBillingAddress();
                        if ($address) {
                            $config['field_value'] = $address->getCountryId();
                        }
                    }
                }
                $config['countries'] = $this->_countryCollectionFactory->create()->loadByStore($this->getStoreId())->toOptionArray(__('-- Please Select --'));
                $config['template'] = 'country.phtml';
                break;
            default:
                $config['template'] = 'text.phtml';
                break;
        }
        $config['template'] = 'VladimirPopov_WebForms::webforms/fields/' . $config['template'];
        $this->setConfig($config);
        $fieldBlock = $this->_layout->createBlock($block_type, null, ['data' => $config]);
        $fieldBlock->setField($this);
        $html = $fieldBlock->toHtml();

        if ($this->getData('validate_regex')) {
            $flags = array();

            $regexp = trim($this->getData('validate_regex'));

            preg_match('/\/([igmy]{1,4})$/', $regexp, $flags);

            // set regex flags
            if (!empty($flags[1])) {
                $flags = $flags[1];
                $regexp = substr($regexp, 0, strlen($regexp) - strlen($flags));
            } else {
                $flags = '';
            }

            if (substr($regexp, 0, 1) == '/' && substr($regexp, strlen($regexp) - 1, strlen($regexp)) == '/')
                $regexp = substr($regexp, 1, -1);
            $regexp = str_replace('\\', '\\\\', $regexp);

            $validate_message = trim(str_replace("'", "\'", $this->getData('validate_message')));

            // custom regex validation
            $validate_script = "<script>require(['VladimirPopov_WebForms/js/validation'],function(Validation){Validation.add('validate-field-{$this->getId()}','{$validate_message}',function(v,elm){
                var r = new RegExp('{$regexp}','{$flags}');
                var isValid = Validation.get('IsEmpty').test(v) || r.test(v);";
            if ($this->getType() == 'select/checkbox' || $this->getType() == 'select/radio') {
                $validate_script .= "
                    isValid = false;
                    var inputs = $$('input[name=\"' + elm.name.replace(/([\\\"])/g, '\\$1') + '\"]');
                    for(var i=0;i<inputs.length;i++) {
                        if((inputs[i].type == 'checkbox' || inputs[i].type == 'radio') && inputs[i].checked == true && r.test(inputs[i].value)) {
                            isValid = true;
                        }
    
                        if(Validation.isOnChange && (inputs[i].type == 'checkbox' || inputs[i].type == 'radio')) {
                            Validation.reset(inputs[i]);
                        }
                    }
                ";
            }
            $validate_script .= "
                return isValid;
            })})</script>";

            $html .= $validate_script;
        }

        if ($field_type == 'number') {
            if (isset($field_value['number_min']) && (!empty($field_value['number_min']) || $field_value['number_min'] == '0')) {
                $validate_message = __('Minimum value is %1', $field_value['number_min']);
                $html .= "
<script>
    require(['VladimirPopov_WebForms/js/validation'],function(Validation){
        Validation.add('validate-field-number-min-{$this->getId()}','{$validate_message}',function(v){return v >= {$field_value['number_min']};})
    })
</script>";
            }
            if (isset($field_value['number_max']) && (!empty($field_value['number_max']) || $field_value['number_max'] == '0')) {
                $validate_message = __('Maximum value is %1', $field_value['number_max']);
                $html .= "
<script>
    require(['VladimirPopov_WebForms/js/validation'],function(Validation){
        Validation.add('validate-field-number-max-{$this->getId()}','{$validate_message}',function(v){return v <= {$field_value['number_max']};})
    })
</script>";
            }
        }


        // activate tinyMCE
        if ($tinyMCE && !$this->_registry->registry('tinyMCE')) {
            $this->_registry->register('tinyMCE', true);
            $tiny_mce = $this->_layout->createBlock('Magento\Framework\View\Element\Template', 'tinyMCE', array())
                ->setTemplate('VladimirPopov_WebForms::webforms/scripts/tiny_mce.phtml');
            $html .= $tiny_mce->toHtml();
        }

        // apply custom field type
        $html_object = new \Magento\Framework\DataObject(array('html' => $html));
        $this->_eventManager->dispatch('webforms_fields_tohtml_html', array('field' => $this, 'html_object' => $html_object));

        return $html_object->getHtml();
    }

    public function duplicate()
    {
        // duplicate field
        $field = $this->_fieldFactory->create()
            ->setData($this->getData())
            ->setId(null)
            ->setName($this->getName() . ' ' . __('(new copy)'))
            ->setIsActive(false)
            ->save();

        // duplicate store data
        $stores = $this->_storeFactory->create()
            ->getCollection()
            ->addFilter('entity_id', $this->getId())
            ->addFilter('entity_type', $this->getEntityType());

        foreach ($stores as $store) {
            $duplicate = $this->_storeFactory->create()
                ->setData($store->getData())
                ->setId(null)
                ->setEntityId($field->getId())
                ->save();
        }

        return $field;
    }

    public function getLogic()
    {
        $collection = $this->_logicFactory->create()->setStoreId($this->getStoreId())->getCollection()->addFilter('field_id', $this->getId());
        return $collection;
    }

    public function getLogicTargetOptionsArray()
    {
        $options = array();
        $webform = $this->_formFactory->create()->setStoreId($this->getStoreId())->load($this->getWebformId());
        $fields_to_fieldsets = $webform->getFieldsToFieldsets(true);

        foreach ($fields_to_fieldsets as $fieldset_id => $fieldset) {
            $field_options = array();
            foreach ($fieldset['fields'] as $field) {
                if ($field->getId() != $this->getId() && $field->getType() != 'hidden')
                    $field_options[] = array('value' => 'field_' . $field->getId(), 'label' => $field->getName());
            }

            if ($fieldset_id) {
                if ($this->getFieldsetId() != $fieldset_id)
                    $options[] = array('value' => 'fieldset_' . $fieldset_id, 'label' => $fieldset['name'] . ' [' . __('Field Set') . ']');
                if (count($field_options)) {
                    $options[] = array('value' => $field_options, 'label' => $fieldset['name']);
                }
            } else {
                foreach ($field_options as $opt) {
                    $options[] = $opt;
                }
            }
        }

        return $options;
    }

    public function prepareResultValue($value)
    {

        switch ($this->getType()) {
            case 'select/contact':
                $contact = $this->getContactArray($value);
                if (!empty($contact["name"])) $value = $contact["name"];
                break;
        }

        $valueObj = new \Magento\Framework\DataObject(array('value' => $value));

        $this->_eventManager->dispatch('webforms_fields_prepare_result_value', array('field' => $this, 'value' => $valueObj));

        return $valueObj->getValue();
    }

    public function getCustomer()
    {
        try {
            return $this->_session->getCustomer();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    public function registry($key = '')
    {
        return $this->_registry->registry($key);
    }

    public function getResultLabel()
    {
        if ($this->getData('result_label')) return $this->getData('result_label');
        return $this->getName();
    }
}

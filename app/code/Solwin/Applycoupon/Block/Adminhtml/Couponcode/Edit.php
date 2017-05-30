<?php
/**
 * Solwin Infotech
 * Solwin Discount Coupon Code Link Extension
 *
 * @category   Solwin
 * @package    Solwin_Applycoupon
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/ 
 */
namespace Solwin\Applycoupon\Block\Adminhtml\Couponcode;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * constructor
     * 
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Couponcode edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'couponcode_id';
        $this->_blockGroup = 'Solwin_Applycoupon';
        $this->_controller = 'adminhtml_couponcode';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Couponcode'));
        $this->buttonList->add(
            'save-and-continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );
        $this->buttonList->update('delete', 'label', __('Delete Couponcode'));
    }
    /**
     * Retrieve text for header element depending on loaded Couponcode
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var \Solwin\Applycoupon\Model\Couponcode $couponcode */
        $couponcode = $this->_coreRegistry
                ->registry('solwin_applycoupon_couponcode');
        if ($couponcode->getId()) {
            return __(
                    "Edit Couponcode '%1'", 
                    $this->escapeHtml($couponcode->getRule_name())
                    );
        }
        return __('New Couponcode');
    }
}
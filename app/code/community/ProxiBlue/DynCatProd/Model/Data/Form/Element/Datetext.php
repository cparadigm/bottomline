<?php

/**
 *
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2014 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Data_Form_Element_Datetext extends Varien_Data_Form_Element_Date
{

    /**
     * Set the value of the element
     *
     * @param string $value
     * @param null   $format
     * @param null   $locale
     *
     * @return \ProxiBlue_DynCatProd_Model_Data_Form_Element_Datetext
     */
    public function setValue($value, $format = null, $locale = null)
    {
        if (is_numeric($value)) {
            $this->_value = $value;

            return $this;
        }

        return parent::setValue($value, $format = null, $locale = null);
    }

    /**
     * Get date value as string.
     * Format can be specified, or it will be taken from $this->getFormat()
     *
     * @param  string $format (compatible with Zend_Date)
     * @return string
     */
    public function getValue($format = null)
    {
        if (empty($this->_value)) {
            return '';
        }
        if (null === $format) {
            $format = $this->getFormat();
        }
        if (is_numeric($this->_value)) {
            return $this->_value;
        }

        return $this->_value->toString($format);
    }

}

<?php

 /**
 * WebShopApps Shipping Module
 *
 * @category    WebShopApps
 * @package     WebShopApps_USPSV2
 * User         Joshua Stewart
 * Date         24/07/2013
 * Time         12:14
 * @copyright   Copyright (c) 2013 Zowta Ltd (http://www.WebShopApps.com)
 *              Copyright, 2013, Zowta, LLC - US license
 * @license     http://www.WebShopApps.com/license/license.txt - Commercial license
 *
 */
class Webshopapps_Wsauspsv2_Model_Shipping_Carrier_Usps extends Mage_Usa_Model_Shipping_Carrier_Usps
{

    /**
     * Parse calculated rates
     *
     * @link http://www.usps.com/webtools/htm/Rate-Calculators-v2-3.htm
     * @param string $response
     * @return Mage_Shipping_Model_Rate_Result
     */
    protected function _parseXmlResponse($response)
    {
        $costArr = array();
        $priceArr = array();
        if (strlen(trim($response)) > 0) {
            if (strpos(trim($response), '<?xml') === 0) {
                if (strpos($response, '<?xml version="1.0"?>') !== false) {
                    $response = str_replace(
                        '<?xml version="1.0"?>',
                        '<?xml version="1.0" encoding="ISO-8859-1"?>',
                        $response
                    );
                }

                $xml = simplexml_load_string($response);

                Mage::helper('wsalogger/log')->postDebug('USPS', 'USPS', $xml);

                if (is_object($xml)) {
                    if (is_object($xml->Number) && is_object($xml->Description) && (string)$xml->Description!='') {
                        $errorTitle = (string)$xml->Description;
                    } elseif (is_object($xml->Package)
                        && is_object($xml->Package->Error)
                        && is_object($xml->Package->Error->Description)
                        && (string)$xml->Package->Error->Description!=''
                    ) {
                        $errorTitle = (string)$xml->Package->Error->Description;
                    } else {
                        $errorTitle = 'Unknown error';
                        $errorRequest = $this->_rawRequest;
                        $errorResponse = $response;
                    }

                    $r = $this->_rawRequest;
                    $allowedMethods = explode(",", $this->getConfigData('allowed_methods'));
                    $allMethods = $this->getCode('method');
                    $newMethod = false;

                    /**
                     * WSA Changes START - strip time in transit estimate to compare with allowed methods
                     */

                    $magentoVersion = Mage::helper('wsalogger')->getNewVersion();

                    if($magentoVersion > 8) {
                        $isUs = $this->_isUSCountry($r->getDestCountryId());
                    } else {
                        $isUs = $r->getDestCountryId() == self::USA_COUNTRY_ID || $r->getDestCountryId() == self::PUERTORICO_COUNTRY_ID;
                    }

                    if ($isUs) {
                        if (is_object($xml->Package) && is_object($xml->Package->Postage)) {
                            foreach ($xml->Package->Postage as $postage) {

                                if($magentoVersion > 9) {
                                    $basicName = $this->_filterServiceName((string)$postage->MailService);
                                } else {
                                    $basicName = Mage::helper('webshopapps_wsauspsv2')->filterServiceName((string)$postage->MailService);
                                }

                                $serviceName = $this->stripTimeStamp($basicName);

                    /**
                     * WSA Changes END
                     */

                                $postage->MailService = $serviceName;
                                if (in_array($serviceName, $allowedMethods)) {
                                    $costArr[$serviceName] = (string)$postage->Rate;
                                    $priceArr[$serviceName] = $this->getMethodPrice(
                                        (string)$postage->Rate,
                                        $serviceName
                                    );
                                } elseif (!in_array($serviceName, $allMethods)) {
                                    $allMethods[] = $serviceName;
                                    $newMethod = true;
                                }
                            }
                            asort($priceArr);
                        }
                    } else {
                        /*
                         * International Rates
                         */
                        if (is_object($xml->Package) && is_object($xml->Package->Service)) {
                            foreach ($xml->Package->Service as $service) {

                                if($magentoVersion > 9) {
                                    $serviceName = $this->_filterServiceName((string)$service->SvcDescription);
                                } else {
                                    $serviceName = Mage::helper('webshopapps_wsauspsv2')->filterServiceName((string)$service->SvcDescription);
                                }

                                $service->SvcDescription = $serviceName;
                                if (in_array($serviceName, $allowedMethods)) {
                                    $costArr[$serviceName] = (string)$service->Postage;
                                    $priceArr[$serviceName] = $this->getMethodPrice(
                                        (string)$service->Postage,
                                        $serviceName
                                    );
                                } elseif (!in_array($serviceName, $allMethods)) {
                                    $allMethods[] = $serviceName;
                                    $newMethod = true;
                                }
                            }
                            asort($priceArr);
                        }
                    }
                    /**
                     * following if statement is obsolete
                     * we don't have adminhtml/config resoure model
                     */
                    if (false && $newMethod) {
                        sort($allMethods);
                        $insert['usps']['fields']['methods']['value'] = $allMethods;
                        Mage::getResourceModel('adminhtml/config')->saveSectionPost('carriers','','',$insert);
                    }
                }
            } else {
                $errorTitle = 'Response is in the wrong format';
                $errorRequest = $this->_rawRequest;
                $errorResponse = $response;
            }

            if(!empty($errorTitle)){
                Mage::helper('wsalogger/log')->postCritical('USPS', 'USPS', $errorTitle);
                Mage::helper('wsalogger/log')->postCritical('USPS', 'USPS Request', $errorRequest);
                Mage::helper('wsalogger/log')->postCritical('USPS', 'USPS Response', $errorResponse);
            }
        }

        $result = Mage::getModel('shipping/rate_result');
        if (empty($priceArr)) {
            $error = Mage::getModel('shipping/rate_result_error');
            $error->setCarrier('usps');
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage($this->getConfigData('specificerrmsg'));
            $result->append($error);
        } else {
            foreach ($priceArr as $method=>$price) {
                $rate = Mage::getModel('shipping/rate_result_method');
                $rate->setCarrier('usps');
                $rate->setCarrierTitle($this->getConfigData('title'));
                $rate->setMethod($method);
                $rate->setMethodTitle(Mage::helper('usa')->__($method));
                $rate->setCost($costArr[$method]);
                $rate->setPrice($price);
                $result->append($rate);
            }
        }

        return $result;
    }

    protected function stripTimeStamp($name){
        $search = array(' 1-Day',' 2-Day',' 3-Day',' Military',' DPO');

        $name = str_replace($search, '', $name);

        return $name;
    }

    /**
     * Get configuration data of carrier - WSA updated with new methods from July API update
     *
     * @param string $type
     * @param string $code
     * @return array|bool
     */
    public function getCode($type, $code='')
    {
        $codes = array(

            'service'=>array(
                'FIRST CLASS' => Mage::helper('usa')->__('First-Class'),
                'PRIORITY'    => Mage::helper('usa')->__('Priority Mail'),
                'EXPRESS'     => Mage::helper('usa')->__('Express Mail'),
                'BPM'         => Mage::helper('usa')->__('Bound Printed Matter'),
                'PARCEL'      => Mage::helper('usa')->__('Parcel Post'),
                'MEDIA'       => Mage::helper('usa')->__('Media Mail'),
                'LIBRARY'     => Mage::helper('usa')->__('Library'),
            ),

            'service_to_code'=>array(
                'First-Class'                                   => 'FIRST CLASS',
                'First-Class Mail International Large Envelope' => 'FIRST CLASS',
                'First-Class Mail International Letter'         => 'FIRST CLASS',
                'First-Class Mail International Package'        => 'FIRST CLASS',
                'First-Class Mail International Parcel'         => 'FIRST CLASS',
                'First-Class Mail'                              => 'FIRST CLASS',
                'First-Class Mail Flat'                         => 'FIRST CLASS',
                'First-Class Mail Large Envelope'               => 'FIRST CLASS',
                'First-Class Mail International'                => 'FIRST CLASS',
                'First-Class Mail Letter'                       => 'FIRST CLASS',
                'First-Class Mail Parcel'                       => 'FIRST CLASS',
                'First-Class Mail Package'                      => 'FIRST CLASS',
                'Standard Post'                    => 'STANDARD POST',
                'Bound Printed Matter'             => 'BPM',
                'Media Mail'                       => 'MEDIA',
                'Library Mail'                     => 'LIBRARY',
                'Priority Mail Express'                                                 => 'EXPRESS',
                'Priority Mail Express PO to PO'                                        => 'EXPRESS',
                'Priority Mail Express Flat Rate Envelope'                              => 'EXPRESS',
                'Priority Mail Express Flat-Rate Envelope Sunday/Holiday Guarantee'     => 'EXPRESS',
                'Priority Mail Express Sunday/Holiday Guarantee'                        => 'EXPRESS',
                'Priority Mail Express Flat Rate Envelope Hold For Pickup'              => 'EXPRESS',
                'Priority Mail Express Hold For Pickup'                                 => 'EXPRESS',
                'Global Express Guaranteed (GXG)'                                       => 'EXPRESS',
                'Global Express Guaranteed Non-Document Rectangular'                    => 'EXPRESS',
                'Global Express Guaranteed Non-Document Non-Rectangular'                => 'EXPRESS',
                'USPS GXG Envelopes'                                                    => 'EXPRESS',
                'Priority Mail Express International'                                   => 'EXPRESS',
                'Priority Mail Express International Flat Rate Envelope'                => 'EXPRESS',
                'Priority Mail Express International Legal Flat Rate Envelope'          => 'EXPRESS',
                'Priority Mail Express International Padded Flat Rate Envelope'         => 'EXPRESS',
                'Priority Mail Express International Flat Rate Boxes'                   => 'EXPRESS',
                'Priority Mail'                                          => 'PRIORITY',
                'Priority Mail Small Flat Rate Box'                      => 'PRIORITY',
                'Priority Mail Medium Flat Rate Box'                     => 'PRIORITY',
                'Priority Mail Large Flat Rate Box'                      => 'PRIORITY',
                'Priority Mail Flat Rate Box'                            => 'PRIORITY',
                'Priority Mail Flat Rate Envelope'                       => 'PRIORITY',
                'Priority Mail International'                            => 'PRIORITY',
                'Priority Mail International Flat Rate Envelope'         => 'PRIORITY',
                'Priority Mail International Small Flat Rate Box'        => 'PRIORITY',
                'Priority Mail International Medium Flat Rate Box'       => 'PRIORITY',
                'Priority Mail International Large Flat Rate Box'        => 'PRIORITY',
                'Priority Mail International Flat Rate Box'              => 'PRIORITY',
            ),

            'first_class_mail_type'=>array(
                'LETTER'      => Mage::helper('usa')->__('Letter'),
                'FLAT'        => Mage::helper('usa')->__('Flat'),
                'PARCEL'      => Mage::helper('usa')->__('Parcel'),
            ),

            'container'=>array(
                'VARIABLE'           => Mage::helper('usa')->__('Variable'),
                'FLAT RATE BOX'      => Mage::helper('usa')->__('Flat-Rate Box'),
                'FLAT RATE ENVELOPE' => Mage::helper('usa')->__('Flat-Rate Envelope'),
                'RECTANGULAR'        => Mage::helper('usa')->__('Rectangular'),
                'NONRECTANGULAR'     => Mage::helper('usa')->__('Non-rectangular'),
            ),

            'containers_filter' => array(
                array(
                    'containers' => array('VARIABLE'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'First-Class Mail Large Envelope',
                                'First-Class Mail Letter',
                                'First-Class Mail Parcel',
                                'First-Class Mail Postcards',
                                'Priority Mail',
                                'Priority Mail Express Hold For Pickup',
                                'Priority Mail Express',
                                'Standard Post',
                                'Media Mail',
                                'Library Mail',
                                'Priority Mail Express Flat Rate Envelope',
                                'First-Class Mail Large Postcards',
                                'Priority Mail Flat Rate Envelope',
                                'Priority Mail Medium Flat Rate Box',
                                'Priority Mail Large Flat Rate Box',
                                'Priority Mail Express Sunday/Holiday Delivery',
                                'Priority Mail Express Sunday/Holiday Delivery Flat Rate Envelope',
                                'Priority Mail Express Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Small Flat Rate Box',
                                'Priority Mail Padded Flat Rate Envelope',
                                'Priority Mail Express Legal Flat Rate Envelope',
                                'Priority Mail Express Legal Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Express Sunday/Holiday Delivery Legal Flat Rate Envelope',
                                'Priority Mail Hold For Pickup',
                                'Priority Mail Large Flat Rate Box Hold For Pickup',
                                'Priority Mail Medium Flat Rate Box Hold For Pickup',
                                'Priority Mail Small Flat Rate Box Hold For Pickup',
                                'Priority Mail Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Gift Card Flat Rate Envelope',
                                'Priority Mail Gift Card Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Window Flat Rate Envelope',
                                'Priority Mail Window Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Small Flat Rate Envelope',
                                'Priority Mail Small Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Legal Flat Rate Envelope',
                                'Priority Mail Legal Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Padded Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Regional Rate Box A',
                                'Priority Mail Regional Rate Box A Hold For Pickup',
                                'Priority Mail Regional Rate Box B',
                                'Priority Mail Regional Rate Box B Hold For Pickup',
                                'First-Class Package Service Hold For Pickup',
                                'Priority Mail Express Flat Rate Boxes',
                                'Priority Mail Express Flat Rate Boxes Hold For Pickup',
                                'Priority Mail Express Sunday/Holiday Delivery Flat Rate Boxes',
                                'Priority Mail Regional Rate Box C',
                                'Priority Mail Regional Rate Box C Hold For Pickup',
                                'First-Class Package Service',
                                'Priority Mail Express Padded Flat Rate Envelope',
                                'Priority Mail Express Padded Flat Rate Envelope Hold For Pickup',
                                'Priority Mail Express Sunday/Holiday Delivery Padded Flat Rate Envelope',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'Priority Mail International Flat Rate Envelope',
                                'Priority Mail International Large Flat Rate Box',
                                'Priority Mail International Medium Flat Rate Box',
                                'Priority Mail International Small Flat Rate Box',
                                'Global Express Guaranteed (GXG)',
                                'USPS GXG Envelopes',
                                'Priority Mail International',
                                'First-Class Mail International Package',
                                'First-Class Mail International Large Envelope',
                                'First-Class Mail International Parcel',
                                'Priority Mail Express International',
                                'Priority Mail Express International Flat Rate Envelope',
                                'Priority Mail Express International Legal Flat Rate Envelope',
                                'Priority Mail Express International Padded Flat Rate Envelope',
                                'Priority Mail Express International Flat Rate Boxes',
                            )
                        )
                    )
                ),
                array(
                    'containers' => array('FLAT RATE BOX'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'Priority Mail Large Flat Rate Box',
                                'Priority Mail Medium Flat Rate Box',
                                'Priority Mail Small Flat Rate Box',

                                'Priority Mail Regional Rate Box A',
                                'Priority Mail Regional Rate Box B',
                                'Priority Mail Regional Rate Box C',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'Priority Mail International Large Flat Rate Box',
                                'Priority Mail International Medium Flat Rate Box',
                                'Priority Mail International Small Flat Rate Box',
                            )
                        )
                    )
                ),
                array(
                    'containers' => array('FLAT RATE ENVELOPE'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'Priority Mail Flat Rate Envelope',
                                'Priority Mail Padded Flat Rate Envelope',
                                'Priority Mail Small Flat Rate Envelope',
                                'Priority Mail Legal Flat Rate Envelope',
                                'Priority Mail Express Flat Rate Envelope',
                                'Priority Mail Express Padded Flat Rate Envelope',
                                'Priority Mail Express Legal Flat Rate Envelope',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'Express Mail International Flat Rate Envelope',
                                'Priority Mail International Flat Rate Envelope',
                            )
                        )
                    )
                ),
                array(
                    'containers' => array('RECTANGULAR'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'Priority Mail Express',
                                'Priority Mail',
                                'Standard Post',
                                'Media Mail',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'USPS GXG Envelopes',
                                'Express Mail International',
                                'Priority Mail International',
                                'First-Class Mail International Package',
                                'First-Class Mail International Parcel',
                            )
                        )
                    )
                ),
                array(
                    'containers' => array('NONRECTANGULAR'),
                    'filters'    => array(
                        'within_us' => array(
                            'method' => array(
                                'Priority Mail Express',
                                'Priority Mail',
                                'Standard Post',
                                'Media Mail',
                            )
                        ),
                        'from_us' => array(
                            'method' => array(
                                'Global Express Guaranteed (GXG)',
                                'USPS GXG Envelopes',
                                'Express Mail International',
                                'Priority Mail International',
                                'First-Class Mail International Package',
                                'First-Class Mail International Parcel',
                            )
                        )
                    )
                ),
            ),

            'size'=>array(
                'REGULAR'     => Mage::helper('usa')->__('Regular'),
                'LARGE'       => Mage::helper('usa')->__('Large'),
            ),

            'machinable'=>array(
                'true'        => Mage::helper('usa')->__('Yes'),
                'false'       => Mage::helper('usa')->__('No'),
            ),

            'delivery_confirmation_types' => array(
                'True' => Mage::helper('usa')->__('Not Required'),
                'False'  => Mage::helper('usa')->__('Required'),
            ),
        );

        $methods = $this->getConfigData('methods');
        if (!empty($methods)) {
            $codes['method'] = explode(",", $methods);
        } else {
            $codes['method'] = array();
        }

        if (!isset($codes[$type])) {
            return false;
        } elseif (''===$code) {
            return $codes[$type];
        }

        if (!isset($codes[$type][$code])) {
            return false;
        } else {
            return $codes[$type][$code];
        }
    }
}
<?php

/**
 * Author: pbk
 */

class CDH
{
    var $url;
    var $app;

    //
    // User Functions
    //

    function CDH($url) {
        $this->url = $url;
        }

    function get_token($email, $campaign_code) {
        $shared_secret = 'Cnc4J7sKBE;PDhAjvR4HTCbTt}c8yRE]dfi3%#?qCZ9d>cT8r8';
        return md5( $email . $campaign_code . $shared_secret, false );
        }

    function set_user($email, $newpassword, $first, $last ) {
        if (class_exists('Mage')) {
            Mage::init();
            $app = Mage::app();
            $website = $app->getWebsite()->getId();
            $store = $app->getStore();

            $customer = Mage::getModel("customer/customer");
            $customer->setWebsiteId($website);

            try {
                $customer->loadByEmail($email);
            } catch (Exception $e1) {
                echo('<br/>Error getting customer:' . $e1->getMessage());
                exit;
            }

            if ($customer->getId()) {
                if ( !is_null($newpassword) ) {
                    syslog(LOG_INFO, 'set Magento password for ' . $customer->getId());
                    $customer->setPassword($newpassword);
                    $customer->save();
                    }
            } else {

                syslog(LOG_INFO, 'created Magento user for ' . $email);

                $customer->setWebsiteId($website)
                    ->setStore($store)
                    ->setFirstname($first)
                    ->setLastname($last)
                    ->setEmail($email)
                    ->setPassword($newpassword);
                try {
                    $customer->save();
                } catch (Exception $e) {
                    echo('<br/>Error adding customer:' . $e->getMessage());
                }

            }

            return $customer->getId();
        }
    }

    function opt_in($email,  $first_name, $middle_name,  $last_name, $subscription_name) {
        return $this->opt($email,  $first_name, $middle_name,  $last_name, $subscription_name, true) ;
        }

    function opt_out($email,  $first_name, $middle_name,  $last_name, $subscription_name) {
        return $this->opt($email,  $first_name, $middle_name,  $last_name, $subscription_name, false) ;
    }
    

    function get_subscriptions( $email, $magento_id, $session_id ) {
        return $this->get_subscriptions_opt( $email, $magento_id, null, $session_id ) ;
        }

    function get_selected_subscriptions( $email, $magento_id, $session_id ) {
        return $this->get_subscriptions_opt( $email, $magento_id, true, $session_id ) ;
        }

    function get_unselected_subscriptions( $email, $magento_id, $session_id ) {
        return $this->get_subscriptions_opt( $email, $magento_id, false, $session_id ) ;
        }

    function get_subscriptions_opt( $email, $magento_id, $opt_value, $session_id ) {

        if ( $opt_value == null ) {
            $query_data = array("external_user_id" => $magento_id, "email" => $email );
            }
        else {
            $query_data = array("email" => $email, "external_user_id" => $magento_id, "opt_in" => $opt_value);
        }
        $curl = curl_init();
        $headers = array(
            'Accept: application/json',
            'sessionID: ' . $session_id,
            'appName: SHOP '
            );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);

        // TODO: Authentication
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        $path = $this->url . 'customer/getCustomerSubs/?' . http_build_query($query_data);

        curl_setopt($curl, CURLOPT_URL, $path);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 7);

        $result = curl_exec($curl);
        syslog(LOG_INFO, 'get subs('.$path.') for ' . $magento_id . '('. $result . ')' ) ;

        curl_close($curl);

        return $result;
    }


    function magento_login_customer($email, $password ) {
        try {
            if (class_exists('Mage')) {
                $websiteId = Mage::app()->getWebsite()->getId();
                Mage::init($websiteId, 'website');
                $session = Mage::getSingleton('customer/session');
                $session->login($email, $password);
                return $session;
                }
            }
        catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
            }
        return null;
    }

    function magento_add_customer($email, $password, $first_name, $middle_name, $last_name ) {
        try {
            if (class_exists('Mage')) {
                $websiteId = Mage::app()->getWebsite()->getId();
                $store = Mage::app()->getStore();

                $customer = Mage::getModel("customer/customer");
                $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($first_name)
                    ->setLastname($last_name)
                    ->setEmail($email)
                    ->setPassword($password);

                $customer->save();
                return true ;
                }
            }
        catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
            }
        return false ;
    }


    //
    // Internal Functions
    //
    function set_subscriptions($email, $magento_id, $first_name, $middle_name,  $last_name, $subscription_array, $session_id ) {

        $xml = new SimpleXMLElement('<xml/>');

        $customer = $xml->addChild('customer');
        $customer->addChild('email', $email);
        $customer->addChild('first_name', $first_name);
        $customer->addChild('middle_name', $middle_name);
        $customer->addChild('last_name', $last_name);
        $customer->addChild('external_user_id', $magento_id);

        $subscriptions = $customer->addChild('subscriptions');
        foreach ($subscription_array as $k => $v) {
            $subscription = $subscriptions->addChild('subscription');
            $subscription->addChild('subscription_name', $k);
            $subscription->addChild('opt_in', $v);
            }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        $headers = array(
            'Content-Type: text/xml',
            'Accept: application/json',
            'sessionID: ' . $session_id,
            'appName: SHOP '
            );
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        // TODO: Authentication
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        $path = $this->url . 'subscription/changeSubscriptions';

        curl_setopt($curl, CURLOPT_URL, $path);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $customer->asXML());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        syslog(LOG_INFO, 'set subs('.$path.' / '.$xml->asXML().') for ' . $magento_id . '('. $result . ')' ) ;

        return $result;
        }

    }
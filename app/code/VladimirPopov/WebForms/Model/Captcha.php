<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Model;

class Captcha
{
    protected $_publicKey;

    protected $_privateKey;

    protected $_theme = 'standard';

    /** @var \Magento\Framework\Math\Random */
    protected $_random;

    protected $_registry;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Math\Random $random
    ) {

        $this->_random = $random;
        $this->_registry = $registry;
    }

    public function setPublicKey($value)
    {
        $this->_publicKey = $value;
    }

    public function setPrivateKey($value)
    {
        $this->_privateKey = $value;
    }

    public function setTheme($value)
    {
        $this->_theme = $value;
    }

    function getCurlData($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $curlData = curl_exec($curl);
        curl_close($curl);
        return $curlData;
    }

    public function verify($response)
    {

        //Get user ip
        $ip = $_SERVER['REMOTE_ADDR'];

        //Build up the url
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $full_url = $url . '?secret=' . $this->_privateKey . '&response=' . $response . '&remoteip=' . $ip;

        //Get the response back decode the json
        $data = json_decode($this->getCurlData($full_url));

        //Return true or false, based on users input
        if (isset($data->success) && $data->success == true) {
            return true;
        }

        return false;
    }

    public function getHtml()
    {
        $output = '';
        $rand = $this->_random->getRandomString(6);
        if (!$this->_registry->registry('webforms_recaptcha_gethtml')) {
            $output .= '<script>var reWidgets =[];</script>';
        }

        $output .= <<<HTML
<script>
    function recaptchaCallback{$rand}(response){
        $('re{$rand}').value = response;
        Validation.validate($('re{$rand}'));
        for(var i=0; i<reWidgets.length;i++){
            if(reWidgets[i].id != '{$rand}')
                grecaptcha.reset(reWidgets[i].inst);
        }
    }
    reWidgets.push({id:'{$rand}',inst : '',callback: recaptchaCallback{$rand}});

</script>
<div id="g-recaptcha{$rand}" class="g-recaptcha"></div>
<input type="hidden" id="re{$rand}" name="recapcha{$rand}" class="required-entry"/>
HTML;

        if (!$this->_registry->registry('webforms_recaptcha_gethtml')) {
            $output .= <<<HTML
<script>
    function recaptchaOnload(){
        for(var i=0; i<reWidgets.length;i++){
            reWidgets[i].inst = grecaptcha.render('g-recaptcha'+reWidgets[i].id,{
                'sitekey' : '{$this->_publicKey}',
                'theme' : '{$this->_theme}',
                'callback': reWidgets[i].callback
            });
        }
    }
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=recaptchaOnload&render=explicit" async defer></script>
HTML;
        }
        if (!$this->_registry->registry('webforms_recaptcha_gethtml')) $this->_registry->register('webforms_recaptcha_gethtml', true);

        return $output;
    }
}
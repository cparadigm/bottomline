<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */
namespace VladimirPopov\WebForms\Model\Mail;

class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{
    public function getMessage(){
        return $this->message;
    }

    public function createAttachment($attachment, $type, $disposition, $encoding, $name){
        $this->message->createAttachment($attachment, $type, $disposition, $encoding, $name);
        return $this;
    }
}
<?php


namespace App\Libraries;


class SMS
{
    public $message;
    public $recipients;
    public $error;

    public function __construct()
    {

    }

    public function sendSMS($message = '', $recipients = '', $selected_gateway = '') : bool
    {
        if(!$message || is_null($message) || $message == '') {
            $message = $this->message;
        }

        if(!$message || is_null($message) || $message == '') {
            $this->error = "No message has been defined";
            return FALSE;
        }
        
        
        if(!$recipients || is_null($recipients) || $recipients == '') {
            $recipients = $this->recipients;
        }

        if(!$recipients || is_null($recipients) || $recipients == '') {
            $this->error = "No recipient(s) have been defined";
            return FALSE;
        }

        $gateways = [
            'africastalking' => [
                'class'     => '\App\Libraries\AtSMS',
                'title'     => 'Africas Talking SMS',
                'description'   => 'Send SMS via the Africas Talking API'
            ]
        ];
        $sms_gateways = apply_filters('sms_gateways', $gateways);
        if(!$sms_gateways || !is_array($sms_gateways)) {
            $this->error = "No SMS gateway has been set up";
            return FALSE;
        }

        if($selected_gateway != '') {
            $active_gateway = $selected_gateway;
        } else {
            $active_gateway = get_option('active_sms_gateway', FALSE);
            if(!$active_gateway) {
                $this->error = "No SMS gateway has been set up";
            }
        }

        if(!isset($sms_gateways[$active_gateway]) && isset($sms_gateways[$active_gateway]['class'])) {
            $this->error = "Selected gateway does not exist";
            return FALSE;
        }
        $gateway = $sms_gateways[$active_gateway]['class'];
        if(!class_exists($gateway)) {
            $this->error = "Gateway class does not exist";
            return FALSE;
        }
        $gateway = new $gateway;
        if($gateway instanceof SmsInterface) {
            $res = $gateway->sendSMS($message, $recipients);
            if($res) {
                return TRUE;
            } else {
                if(isset($gateway->error)) {
                    $this->error = $gateway->error;
                } else {
                    $this->error = "An error occured";
                }
                return FALSE;
            }
        } else {
            $this->error = "$gateway is not an instance of SmsInterface";
            return FALSE;
        }

        
    }
}
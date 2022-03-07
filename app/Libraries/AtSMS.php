<?php


namespace App\Libraries;


use AfricasTalking\SDK\AfricasTalking;

class AtSMS implements SmsInterface
{
    public $error;
    public function __construct()
    {

    }

    public function sendSMS($message = '', $recipients = ''): bool
    {
        $username = get_option('at_sms_username', FALSE);
        $key = get_option('at_sms_key', FALSE);
        $senderId = get_option('at_sms_sender_id', FALSE);
        if(!$username || !$key) {
            $this->error = "API Username or API Key is missing";
            return FALSE;
        }
        $at = new AfricasTalking($username, $key);
        $sms = $at->sms();
        $options = [
            'to'    => $recipients,
            'message' => $message
        ];
        if($senderId && $senderId != '') {
            $options['from'] = $senderId;
        }
        if($sms->send($options)) {
            return TRUE;
        }

        return FALSE;
    }
}
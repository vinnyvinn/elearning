<?php


namespace App\Libraries;


interface SmsInterface
{
    public function sendSMS($message = '', $recipients = '') : bool ;
}
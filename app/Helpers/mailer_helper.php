<?php

use PHPMailer\PHPMailer\PHPMailer;

function getMailer() {
    $mailer = new PHPMailer();

    //setup PHPMailer
    try {
        $mailer->clearAddresses();
        $mailer->isHTML(true);
        $mailer->setFrom('from@example.com', 'Mailer');

        return $mailer;
    } catch (Exception $e) {
        return false;
    }
}
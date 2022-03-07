<?php


namespace App\Entities;


use App\Models\Sections;
use CodeIgniter\Entity;

class Payment extends Entity
{
    public function getClass()
    {
        if($this->attributes['class']) {
            return (new \App\Models\Classes())->find($this->attributes['class']);
        }

        return FALSE;
    }
    public function getSection()
    {
        if($this->attributes['section']) {
            return (new Sections())->find($this->attributes['section']);
        }

        return FALSE;
    }

    public function daysPastDeadline($studentID)
    {
        $deadline = $this->attributes['deadline'];
        $deadline = date_create_from_format('m/d/Y', $deadline);
        $today = date_create_from_format('m/d/Y', date('m/d/Y'));

        if ($deadline->getTimestamp() >= time()) {
            return 0;
        }

        $paymentSubmission = new \App\Models\PaymentSubmission();
        $sub = $paymentSubmission->where('student', $studentID)
            ->where('payment', $this->attributes['id'])
            ->where('status', 1)
            ->get()->getFirstRow('\App\Entities\PaymentSubmission');

        if ($sub) {
            $today = date_create_from_format('Y-m-d', $sub->payment_date);
        }

        $difference = date_diff($deadline, $today);

        $difference = $difference->days;

        return $difference;
    }
}
<?php


namespace App\Entities;


class PaymentSubmission extends \CodeIgniter\Entity
{
    public function getSlipPath()
    {
        return FCPATH.'uploads/deposit-slips/'.$this->attributes['deposit_slip'];
    }
}
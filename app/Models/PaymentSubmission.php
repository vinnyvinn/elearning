<?php


namespace App\Models;


class PaymentSubmission extends \CodeIgniter\Model
{
    protected $primaryKey = 'id';
    protected $table = 'payments_submission';
    protected $allowedFields = ['payment', 'student', 'class', 'month', 'deposit_slip', 'status', 'reference', 'payment_date'];

    protected $returnType = '\App\Entities\PaymentSubmission';
}
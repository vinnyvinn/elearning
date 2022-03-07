<?php


namespace App\Models;


use CodeIgniter\Model;

class FeeCollection extends Model
{
    protected $table = 'fee_payment';
    protected $primaryKey = 'id';

    protected $allowedFields = ['session', 'student', 'description', 'date', 'amount'];

    protected $validationRules = [
        'date'  => ['label' => 'Payment Date', 'rules' => 'trim|required'],
        'amount'  => ['label' => 'Amount', 'rules' => 'trim|required|is_numeric'],
    ];
}
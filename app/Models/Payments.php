<?php


namespace App\Models;


use CodeIgniter\Model;

class Payments extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';

    protected $allowedFields = ['session', 'class', 'section', 'amount', 'description', 'deadline', 'payment_month'];

    protected $returnType = "\App\Entities\Payment";
}
<?php


namespace App\Models;


use CodeIgniter\Model;

class Accounting extends Model
{
    protected $table = 'fees';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\Fee';

    protected $allowedFields = ['session', 'semester', 'class', 'section', 'student', 'description', 'amount'];

    protected $validationRules = [
        'session'   => ['label' => 'Session', 'rules' => 'trim|required|is_natural_no_zero'],
        'description'   => ['label' => 'Description', 'rules' => 'trim|required'],
        'amount'   => ['label' => 'Amount', 'rules' => 'trim|required|is_numeric']
    ];
}
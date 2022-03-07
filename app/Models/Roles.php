<?php


namespace App\Models;


use CodeIgniter\Model;

class Roles extends Model
{
    protected $table = 'groups';

    protected $returnType = 'App\Entities\Roles';

    protected $allowedFields = ['name', 'description', 'capabilities'];

    protected $validationRules    = [
        'name'     => 'required|alpha_numeric_space',
        'description'   => 'required'
    ];
}
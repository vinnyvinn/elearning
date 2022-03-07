<?php

namespace App\Models;

use CodeIgniter\Model;

class Quarters extends Model
{
    protected $table = 'quarters';
    protected $primaryKey = 'id';

    protected $allowedFields = ['session', 'name', 'semester'];

    protected $returnType = '\App\Entities\Quarter';
}
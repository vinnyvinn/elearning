<?php


namespace App\Models;


class Evaluations extends \CodeIgniter\Model
{
    protected $table = 'evaluations';

    protected $primaryKey = 'id';

    protected $allowedFields = ['description'];
}
<?php


namespace App\Models;


use CodeIgniter\Model;

class Departments extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'head'];

    protected $returnType = '\App\Entities\Department';
}
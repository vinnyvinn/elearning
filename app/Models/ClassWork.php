<?php


namespace App\Models;


use CodeIgniter\Model;

class ClassWork extends Model
{
    protected $table = 'classwork';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'class', 'section', 'deadline', 'semester', 'given'];

    protected $returnType = '\App\Entities\ClassWork';
}
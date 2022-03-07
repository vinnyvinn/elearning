<?php


namespace App\Models;


use CodeIgniter\Model;

class ClassGroups extends Model
{
    protected $table = 'class_groups';
    protected $primaryKey = 'id';

    protected $allowedFields = ['section', 'name'];

    protected $returnType = '\App\Entities\ClassGroup';
}
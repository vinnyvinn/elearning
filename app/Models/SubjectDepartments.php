<?php


namespace App\Models;


use CodeIgniter\Model;

class SubjectDepartments extends Model
{
    protected $table = 'subject_departments';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\SubjectDepartment';

    protected $allowedFields = ['dept_id', 'subject'];
}
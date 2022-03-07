<?php


namespace App\Models;


use CodeIgniter\Model;

class Sections extends Model
{
    protected $table = 'sections';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\Section';

    protected $allowedFields = ['name', 'class', 'maximum_students', 'advisor','session'];

    protected $validationRules = [
        'name'  => ['label' => 'Section Name', 'rules' => 'trim|required'],
        'class' => ['label' => 'Class', 'rules' => 'is_natural_no_zero'],
        'maximum_students' => ['label' => 'Maximum number of students', 'rules' => 'required|is_natural_no_zero']
    ];
}
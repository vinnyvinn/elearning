<?php


namespace App\Models;


use CodeIgniter\Model;

class Semesters extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'id';

    protected $allowedFields = ['session', 'name', 'closing_date', 'opening_date'];

    protected $returnType = '\App\Entities\Semester';
}
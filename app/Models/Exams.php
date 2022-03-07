<?php


namespace App\Models;


use CodeIgniter\Model;

class Exams extends Model
{
    protected $table = 'exams';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'session', 'starting_date', 'ending_date', 'semester', 'quarter', 'class', 'section'];

    protected $returnType = '\App\Entities\Exam';
}
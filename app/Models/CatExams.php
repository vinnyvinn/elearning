<?php


namespace App\Models;


class CatExams extends \CodeIgniter\Model
{
    protected $table = 'cat_exams';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\CatExam';
    protected $allowedFields = ['name', 'class', 'starting_date', 'ending_date', 'deadline', 'semester'];
}
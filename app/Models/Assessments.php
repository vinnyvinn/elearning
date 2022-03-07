<?php


namespace App\Models;


use CodeIgniter\Model;

class Assessments extends Model
{
    protected $table = 'continuous_assessment';
    protected $primaryKey = 'id';

    protected $allowedFields = ['student_id', 'class', 'section', 'subject', 'session', 'month', 'week', 'worksheet', 'classwork_1', 'classwork_2', 'homework_1', 'homework_2', 'quiz_1', 'ex_book', 'conduct', 'bonus', 'assignment', 'quiz_of_10', 'total'];

    protected $returnType = '\App\Entities\Assessment';
}
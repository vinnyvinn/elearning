<?php


namespace App\Models;


class YearlyStudentEvaluation extends \CodeIgniter\Model
{
    protected $table = 'yearly_student_evaluations';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','first_sem_evaluation','second_sem_evaluation','session','class','section'];
}
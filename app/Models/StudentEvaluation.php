<?php


namespace App\Models;


class StudentEvaluation extends \CodeIgniter\Model
{
    protected $table = 'student_evaluations';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','remark','session','class','section','first_sem_tardy','second_sem_tardy','first_sem_absent','second_sem_absent'];
}
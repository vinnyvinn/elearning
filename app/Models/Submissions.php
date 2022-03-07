<?php


namespace App\Models;


use CodeIgniter\Model;

class Submissions extends Model
{
    protected $table = 'assignment_submissions';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Submission';

    protected $allowedFields = ['student_id', 'assignment_id', 'subject_id', 'file', 'note', 'marks_awarded', 'remarks'];

}
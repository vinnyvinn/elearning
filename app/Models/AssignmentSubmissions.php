<?php


namespace App\Models;


class AssignmentSubmissions extends \CodeIgniter\Model
{
    protected $table = 'assignments_submissions';
    protected $primaryKey = 'id';

    protected $allowedFields = ['student_id', 'assignment_id','answer','mark_per_question', 'submitted_on','class_id','section_id'];
    protected $returnType = '\App\Entities\AssignmentSubmission';
}
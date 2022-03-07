<?php


namespace App\Models;


class AssignmentSubmissionsMarked extends \CodeIgniter\Model
{
    protected $table = 'assignment_submissions_marked';
    protected $primaryKey = 'id';

    protected $allowedFields = ['student_id', 'submission_id','assignment_id','answer','mark_per_question', 'scored','class_id','section_id'];
    protected $returnType = '\App\Entities\AssignmentSubmissionMarked';
}
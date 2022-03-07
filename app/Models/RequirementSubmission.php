<?php


namespace App\Models;


use CodeIgniter\Model;

class RequirementSubmission extends Model
{
    protected $table = 'requirements_submissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['session', 'class', 'section', 'student', 'parent_check', 'parent_comment', 'parent_check', 'parent_comment',
        'teacher_check', 'teacher_comment', 'requirement'];
}
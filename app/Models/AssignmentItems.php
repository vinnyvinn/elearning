<?php


namespace App\Models;


class AssignmentItems extends \CodeIgniter\Model
{
    protected $table = 'assignment_items';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\AssignmentItem';
    protected $allowedFields = ['question', 'question_number','instructions','precautions','class', 'section', 'items', 'subject', 'out_of', 'duration', 'name', 'semester', 'given', 'deadline', 'session', 'published'];
}
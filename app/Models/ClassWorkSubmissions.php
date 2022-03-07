<?php


namespace App\Models;


class ClassWorkSubmissions extends \CodeIgniter\Model
{
    protected $table = 'classwork_submissions';
    protected $primaryKey = 'id';

    protected $returnType = 'object';

    protected $allowedFields = ['class_work', 'student_id', 'classwork_item', 'subject', 'answers', 'score', 'submitted_on', 'correct_answers', 'mark_per_question'];
}
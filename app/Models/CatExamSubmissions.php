<?php


namespace App\Models;


class CatExamSubmissions extends \CodeIgniter\Model
{
    protected $primaryKey = 'id';
    protected $table = 'cat_exam_submissions';

    protected $allowedFields = ['student_id', 'cat_exam', 'cat_exam_item', 'subject', 'answers', 'score', 'correct_answers', 'mark_per_question', 'submitted_on'];
    protected $returnType = '\App\Entities\CatExamSubmission';
}
<?php


namespace App\Models;


class QuizSubmissions extends \CodeIgniter\Model
{
    protected $table = 'quiz_submissions';
    protected $primaryKey = 'id';

    protected $allowedFields = ['student_id', 'quiz', 'quiz_item', 'subject', 'answers', 'score', 'correct_answers', 'mark_per_question', 'submitted_on'];
    protected $returnType = '\App\Entities\QuizItem';
}
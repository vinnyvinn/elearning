<?php


namespace App\Models;


class QuizItems extends \CodeIgniter\Model
{
    protected $table = 'quiz_items';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\QuizItem';
    protected $allowedFields = ['quiz', 'class', 'section', 'items', 'subject', 'out_of', 'duration', 'name', 'semester', 'given', 'deadline', 'session', 'published'];
}
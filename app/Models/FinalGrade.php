<?php


namespace App\Models;


class FinalGrade extends \CodeIgniter\Model
{
    protected $table = 'final_grade';
    protected $primaryKey = 'id';

    protected $allowedFields = ['student', 'class', 'section', 'score', 'subject', 'session', 'semester', 'out_of'];

    protected $returnType = 'object';
}
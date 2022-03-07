<?php


namespace App\Models;


class AssessmentResults extends \CodeIgniter\Model
{
    protected $table = 'assessments_results';
    protected $primaryKey = 'id';
    protected $returnType = '\App\Entities\AssessmentResult';

    protected $allowedFields = ['session', 'name', 'student', 'class', 'section', 'subject', 'items', 'score', 'out_of', 'semester'];
}
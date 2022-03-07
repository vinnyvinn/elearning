<?php


namespace App\Models;


class ManualAssessments extends \CodeIgniter\Model
{
    protected $table = 'manual_assessments';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    protected $allowedFields = ['session', 'class', 'student', 'subject', 'results', 'total', 'semester','quarter','section','given_total','desired_total','converted_total'];
}
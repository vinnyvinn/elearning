<?php


namespace App\Models;


class SubjectAnalysis extends \CodeIgniter\Model
{
    protected $table = 'subject_analysis';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','class','section','semester','session','subject','total','grade'];
}

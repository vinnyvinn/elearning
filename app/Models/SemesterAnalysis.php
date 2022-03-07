<?php


namespace App\Models;


class SemesterAnalysis extends \CodeIgniter\Model
{
    protected $table = 'semester_analysis';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','class','section','semester','session','total_marks','average'];
}

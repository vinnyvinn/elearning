<?php


namespace App\Models;


class SemesterRanking extends \CodeIgniter\Model
{
    protected $table = 'semester_ranking';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','class','section','semester','session','rank'];
}

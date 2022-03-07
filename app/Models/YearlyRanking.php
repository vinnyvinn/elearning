<?php


namespace App\Models;


class YearlyRanking extends \CodeIgniter\Model
{
    protected $table = 'yearly_ranking';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','class','section','session','average','total','rank'];
}

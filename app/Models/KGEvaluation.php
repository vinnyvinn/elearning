<?php


namespace App\Models;


class KGEvaluation extends \CodeIgniter\Model
{
    protected $table = 'kg_evaluations';

    protected $primaryKey = 'id';

    protected $allowedFields = ['description','category_id','sub_category_id'];
}
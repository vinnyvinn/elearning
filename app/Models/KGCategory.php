<?php


namespace App\Models;


class KGCategory extends \CodeIgniter\Model
{
    protected $table = 'kg_categories';

    protected $primaryKey = 'id';

    protected $allowedFields = ['name','sub_category_id'];
}
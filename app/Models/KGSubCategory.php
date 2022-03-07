<?php


namespace App\Models;


class KGSubCategory extends \CodeIgniter\Model
{
    protected $table = 'kg_sub_categories';

    protected $primaryKey = 'id';

    protected $allowedFields = ['name'];
}
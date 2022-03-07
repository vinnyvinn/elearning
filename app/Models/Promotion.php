<?php


namespace App\Models;


class Promotion extends \CodeIgniter\Model
{
    protected $table = 'promotions';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','average_mark','promoted','old_class','new_class','old_section','new_section','old_session','new_session'];
}
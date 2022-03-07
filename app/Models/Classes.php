<?php


namespace App\Models;


use CodeIgniter\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $allowedFields = ['name', 'session','pass_mark','weight','type','grading'];

    protected $returnType = '\App\Entities\Classes';
}
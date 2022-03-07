<?php


namespace App\Models;


use CodeIgniter\Model;

class Subjects extends Model
{
    protected $table = 'subjects';

    protected $allowedFields = ['name'];

    protected $returnType = '\App\Entities\Subject';
}
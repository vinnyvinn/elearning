<?php


namespace App\Models;


class Quizes extends \CodeIgniter\Model
{
    protected $table = 'quizes';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'class', 'section', 'deadline', 'semester', 'given'];

    protected $returnType = '\App\Entities\Quiz';
}
<?php


namespace App\Models;


class Slides extends \CodeIgniter\Model
{
    protected $table = 'slides';
    protected $returnType = '\App\Entities\Slide';
    protected $primaryKey = 'id';

    protected $allowedFields = ['image', 'title', 'description'];
}
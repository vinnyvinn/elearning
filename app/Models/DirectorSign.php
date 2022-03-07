<?php


namespace App\Models;


class DirectorSign extends \CodeIgniter\Model
{
    protected $table = 'director_sign';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','session','class','section','is_signed'];
}
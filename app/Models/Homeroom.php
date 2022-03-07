<?php


namespace App\Models;


class Homeroom extends \CodeIgniter\Model
{
    protected $table = 'homeroom';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','first_sem_comment','second_sem_comment','session','class','section','is_signed','first_sem_sign','second_sem_sign'];
}
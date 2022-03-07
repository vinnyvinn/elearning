<?php


namespace App\Models;


class Notices extends \CodeIgniter\Model
{
    protected $table = 'notices';

    protected $primaryKey = 'id';

    protected $allowedFields = ['date_created', 'info', 'public', 'title', 'image','session'];

    protected $returnType = '\App\Entities\Notice';

}
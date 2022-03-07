<?php


namespace App\Models;


use CodeIgniter\Model;

class Files extends Model
{
    protected $table = 'files';
    protected $primaryKey = 'id';

    protected $allowedFields = ['type', 'uid', 'file', 'description'];

    protected $returnType = '\App\Entities\File';
}
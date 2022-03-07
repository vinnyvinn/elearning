<?php


namespace App\Models;


use CodeIgniter\Model;

class Assignments extends Model
{
    protected $table = 'assignments';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Assignment';

    protected $allowedFields = ['class', 'section', 'subject', 'description', 'books', 'file', 'deadline', 'semester', 'session', 'out_of'];
}
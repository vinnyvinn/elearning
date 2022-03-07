<?php


namespace App\Models;


use CodeIgniter\Model;

class Messages extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['teacher', 'parent', 'student', 'direction', 'message', 'attachment', 'for_student','session'];

    protected $returnType = '\App\Entities\Message';
}
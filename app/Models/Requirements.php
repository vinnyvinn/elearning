<?php


namespace App\Models;


use CodeIgniter\Model;

class Requirements extends Model
{
    protected $table = 'requirements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['session', 'class', 'section', 'item', 'deadline', 'description', 'parent_check', 'parent_comment',
        'teacher_check', 'teacher_comment', 'supervisor'];

    protected $returnType = '\App\Entities\Requirement';
}
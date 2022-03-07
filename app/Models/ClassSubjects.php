<?php


namespace App\Models;


use CodeIgniter\Model;

class ClassSubjects extends Model
{
    protected $table = 'class_subjects';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\ClassSubject';

    protected $allowedFields = ['class', 'subject', 'optional', 'pass_mark','grading','kg','session'];
}
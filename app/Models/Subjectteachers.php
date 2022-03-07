<?php


namespace App\Models;


use CodeIgniter\Model;

class Subjectteachers extends Model
{
    protected $table = 'subject_teacher';
    protected $primaryKey = 'id';

    protected $allowedFields = ['class_id', 'subject_id', 'section_id', 'teacher_id'];

    protected $returnType = 'App\Entities\Subjectteacher';
}
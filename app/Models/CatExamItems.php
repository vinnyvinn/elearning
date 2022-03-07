<?php


namespace App\Models;


class CatExamItems extends \CodeIgniter\Model
{
    protected $table = 'cat_exam_items';
    protected $primaryKey = 'id';

    protected $allowedFields = ['cat_exam', 'class', 'section', 'items', 'subject', 'out_of',
        'duration', 'session', 'semester', 'name', 'given', 'deadline', 'published'];

    protected $returnType = '\App\Entities\CatExamItem';
}
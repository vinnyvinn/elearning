<?php


namespace App\Models;


class ClassWorkItems extends \CodeIgniter\Model
{
    protected $table = 'classwork_items';
    protected $primaryKey = 'id';
    protected $returnType = '\App\Entities\ClassWorkItems';

    protected $allowedFields = ['class_work', 'class', 'section', 'items', 'subject', 'out_of', 'duration', 'semester', 'given', 'deadline', 'session', 'name', 'published'];
}
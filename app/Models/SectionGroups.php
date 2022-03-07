<?php


namespace App\Models;


use CodeIgniter\Model;

class SectionGroups extends Model
{
    protected $table = 'section_groups';
    protected $primaryKey = 'id';

    protected $allowedFields = ['group', 'student', 'section'];

    protected $returnType = '\App\Entities\SectionGroup';
}
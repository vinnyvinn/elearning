<?php


namespace App\Models;


class StudentComment extends \CodeIgniter\Model
{
    protected $table = 'student_comments';

    protected $primaryKey = 'id';

    protected $allowedFields = ['description'];
}
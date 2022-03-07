<?php


namespace App\Models;


use CodeIgniter\Model;

class StudentArchives extends Model
{
    protected $table = 'students_archives';
    protected $primaryKey = 'id';

    protected $allowedFields = ['admission_number', 'session', 'user_id', 'class', 'section', 'parent', 'contact', 'active', 'admission_date'];
}
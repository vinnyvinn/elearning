<?php


namespace App\Models;


use CodeIgniter\Model;

class Timetable extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'timetable';

    protected $returnType = '\App\Entities\Timetable';
}
<?php


namespace App\Models;


use CodeIgniter\Model;

class ExamSchedule extends Model
{
    protected $table = 'exams_timetable';
    protected $primaryKey = 'id';

    protected $allowedFields = ['exam', 'class', 'day', 'time', 'subject'];

    protected $returnType = '\App\Entities\ExamSchedule';

    protected $validationRules = [
        'exam'         => 'required|is_natural_no_zero',
        'class'        => 'required|is_natural_no_zero',
        'day'          => 'required|is_natural_no_zero',
        'time'         => 'required',
        'subject'      => 'required'
    ];
}
<?php


namespace App\Models;


use CodeIgniter\Model;

class LessonPlan extends Model
{
    protected $table = 'lesson_plans';
    protected $primaryKey = 'id';

    protected $allowedFields = ['session', 'class', 'section', 'subject', 'month', 'week', 'unit', 'duration', 'day', 'objectives', 'intro', 'presentation', 'stabilization', 'evaluation'];
}
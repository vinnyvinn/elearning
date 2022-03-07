<?php


namespace App\Models;


use CodeIgniter\Model;

class AspSchedules extends Model
{
    protected $table = 'asp_schedule';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\AspSchedule';
}
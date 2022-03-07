<?php

namespace App\Entities;

use App\Models\Departure;
use App\Models\Students;
use CodeIgniter\Entity;

class Depart extends Entity
{


    public function getAllStudentsDepartures()
    {
        $depart = (new Departure())->select('student')->where('type','departure')->findAll();

        if ($depart){
            $stids = array_column($depart,'student');
            return (new Students())->whereIn('id',$stids)->findAll();
        }
        return [];
    }

    public function getStudent_()
    {
       return (new Students())->find($this->attributes['student']);
    }

}
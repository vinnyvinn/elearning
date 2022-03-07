<?php


namespace App\Entities;


use App\Models\Quarters;
use App\Models\Sessions;
use CodeIgniter\Entity;

class Semester extends Entity
{
    public function getSession()
    {
        return (new Sessions())->find($this->attributes['session']);
    }

    public function getQuarters()
    {
        return (new Quarters())->where('semester',$this->attributes['id'])->findAll();
    }
}
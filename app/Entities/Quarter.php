<?php

namespace App\Entities;

use App\Models\Semesters;
use App\Models\Sessions;
use CodeIgniter\Entity;

class Quarter extends Entity
{

    public function getSession()
    {
        return (new Sessions())->find($this->attributes['session']);
    }

    public function getSemester()
    {
        return (new Semesters())->find($this->attributes['semester']);
    }

}
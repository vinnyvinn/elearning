<?php


namespace App\Entities;


use App\Models\Sections;
use CodeIgniter\Entity;

class Requirement extends Entity
{
    public function getClass()
    {
        if($this->attributes['class']) {
            return (new \App\Models\Classes())->find($this->attributes['class']);
        }

        return FALSE;
    }
    public function getSection()
    {
        if($this->attributes['section']) {
            return (new Sections())->find($this->attributes['section']);
        }

        return FALSE;
    }
}
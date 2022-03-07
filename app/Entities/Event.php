<?php


namespace App\Entities;


use App\Models\Sections;
use Cassandra\RetryPolicy\Fallthrough;
use CodeIgniter\Entity;

class Event extends Entity
{
    protected $dates = ['starting_date', 'ending_date'];

    public function getClass()
    {
        if ($class = $this->attributes['class']) {
            return (new \App\Models\Classes())->find($class);
        }

        return FALSE;
    }

    public function getSection()
    {
        if ($section = $this->attributes['section']) {
            return (new Sections())->find($section);
        }

        return FALSE;
    }
}
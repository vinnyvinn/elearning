<?php


namespace App\Entities;


use CodeIgniter\Entity;

class Contact extends Entity
{
    public function getName()
    {
        return $this->attributes['surname'].' '.$this->attributes['first_name'].' '.$this->attributes['last_name'];
    }
}
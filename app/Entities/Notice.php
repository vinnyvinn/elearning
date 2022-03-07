<?php


namespace App\Entities;


class Notice extends \CodeIgniter\Entity
{
    public function getImage()
    {
        if ($this->attributes['image']) {
            return base_url('uploads/'.$this->attributes['image']);
        }

        return false;
    }

    public function getImagePath()
    {
        return FCPATH.'uploads/'.$this->attributes['image'];
    }
}
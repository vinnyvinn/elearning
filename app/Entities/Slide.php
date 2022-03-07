<?php


namespace App\Entities;


use CodeIgniter\Entity;

class Slide extends Entity
{
    public function getImage()
    {
        if($image = $this->attributes['image']) {
            return base_url('uploads/'.$image);
        }

        return '';
    }

    public function getPath()
    {
        return FCPATH.'uploads/'.$this->attributes['image'];
    }
}
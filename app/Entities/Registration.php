<?php


namespace App\Entities;


class Registration extends \CodeIgniter\Entity
{
    public function getName()
    {
        return $this->attributes['first_name'].' '.$this->attributes['middle_name'].' '.$this->attributes['last_name'];
    }

    public function getParent()
    {
        $info = json_decode($this->attributes['info']);
        if(isset($info->parent)) {
            return $info->parent;
        }

        return FALSE;
    }

    public function getInfo()
    {
        return json_decode($this->attributes['info']);
    }
}
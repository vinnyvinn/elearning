<?php


namespace App\Entities;


use CodeIgniter\Entity;

class File extends Entity
{
    public function getFilename()
    {
        return $this->attributes['file'];
    }

    public function getFile()
    {
        return site_url('uploads/files/'.$this->attributes['file']);
    }
}
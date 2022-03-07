<?php


namespace App\Entities;


use CodeIgniter\Entity;

class Roles extends Entity
{
    public function getCapabilities() {
        if($this->attributes['capabilities'] && $this->attributes['capabilities'] != '') {
            return json_decode($this->attributes['capabilities'], true);
        }
        return [];
    }

    public function setCapabilities($caps = array()) {
        $this->attributes['capabilities'] = json_encode($caps);
    }
}
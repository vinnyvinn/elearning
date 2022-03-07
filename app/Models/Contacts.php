<?php


namespace App\Models;


use CodeIgniter\Model;

class Contacts extends Model
{
    protected $table = 'emergency_contacts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['surname', 'first_name', 'last_name', 'phone_mobile', 'phone_work', 'subcity', 'woreda', 'house_number'];

    protected $returnType = '\App\Entities\Contact';
}
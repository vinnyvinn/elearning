<?php


namespace App\Models;


use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\User';

    protected $allowedFields = ['username', 'email', 'password', 'surname', 'first_name', 'last_name', 'gender', 'company', 'phone', 'avatar', 'active'];

    protected $validationRules = [
        'surname' => ['label' => 'Surname', 'rules' => 'required'],
        'first_name' => ['label' => 'First Name', 'rules' => 'required'],
        'last_name' => ['label' => 'Last Name', 'rules' => 'required'],
        'gender' => ['label' => 'Gender', 'rules' => 'in_list[Male,Female]|permit_empty'],
    ];
}
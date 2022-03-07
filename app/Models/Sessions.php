<?php


namespace App\Models;


class Sessions extends  \CodeIgniter\Model
{
    protected $table = 'sessions';

    protected $allowedFields = ['name', 'active','session_type','year'];

    protected $returnType = 'App\Entities\Session';

    protected $validationRules = [
        'name'  => ['label' => 'Session Name', 'rules' => 'trim|required|is_unique[sessions.name]']
    ];

}
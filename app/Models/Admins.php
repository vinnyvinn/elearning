<?php


namespace App\Models;


use App\Libraries\IonAuth;
use CodeIgniter\Model;

class Admins extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\User';

    protected $allowedFields = ['username', 'email', 'password', 'surname', 'first_name', 'last_name', 'gender', 'company', 'phone', 'avatar'];

    public function findAll(int $limit = 0, int $offset = 0)
    {

        $users = (new IonAuth())->users(1)->result();

        $return = [];
        if($users && count($users) > 0) {
            foreach ($users as $user) {
                $return[] = (new User())->find($user->id);
            }
        }

        return $return;
    }

    public function create()
    {

    }
}
<?php


namespace App\Models;


use App\Libraries\IonAuth;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Parents extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = '\App\Entities\Parents';

    public $builder;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        //$this->builder = \Config\Database::connect()->table('users_groups')->where('group_id', 4);
    }

    public function getParents()
    {
        $users = (new IonAuth())->users(4)->result();
        $parents_arr = [];
        $students = (new Students())->where('session',active_session())->findAll();
        $counter = 0;
        if($students && count($students) > 0) {
            foreach ($students as $student) {
                if (isset($student->parent->id) && $student->parent){
                    if(isset($parents_arr[$student->parent->id])){
                    }else {
                        $parents_arr[$student->parent->id] = $student->parent;
                        $counter++;
                    }
                }
            }
        }
        return $parents_arr;
    }

    public function getParents_()
    {
        $users = (new IonAuth())->users(4)->result();
        $parents_arr = [];
        $student_model = new Students();
        if (count(departedIds()) > 0)
            $student_model->whereNotIn('id',departedIds());
        $students = $student_model->where('session',active_session())->findAll();
        $counter = 0;
        if($students && count($students) > 0) {
            foreach ($students as $student) {
                if (isset($student->parent->id) && $student->parent){
                    if(isset($parents_arr[$student->parent->id])){
                    }else {
                        $parents_arr[$student->parent->id] = $student->parent;
                        $counter++;
                    }
                }
            }
        }
        return $parents_arr;
    }
}
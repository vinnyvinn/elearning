<?php


namespace App\Models;


class Departure extends \CodeIgniter\Model
{
    protected $table = 'departures';

    protected $primaryKey = 'id';

    protected $allowedFields = ['student','session','type','count','class','semester'];

    public $returnType = '\App\Entities\Depart';

    public function getAllStudents()
    {
        $depart = (new Departure())->select('student')->where('type','transcript')->findAll();
        if ($depart){
            $stids = array_column($depart,'student');
            return (new Students())->whereIn('id',$stids)->findAll();
        }
        return [];
    }
}
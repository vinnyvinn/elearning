<?php


namespace App\Entities;


use App\Models\ClassSubjects;
use App\Models\Contacts;
use App\Models\Files;
use App\Models\Subjectteachers;
use CodeIgniter\Entity;

class Teacher extends Entity
{
    public function getProfile()
    {
        return (new \App\Models\User())->find($this->attributes['user_id']);
    }

    public function getSubjects()
    {
        $tt = (new Subjectteachers())->where('teacher_id', $this->attributes['id'])->where('session',active_session())->findAll();
//        $subs = [];
//        foreach ($tt as $t) {
//            $subs[] = (new ClassSubjects())->find($t->subject_id);
//        }

        return $tt;
    }

    public function getContact()
    {
        $contact = (new Contacts())->where('id', $this->attributes['contact'])->get()->getLastRow('\App\Entities\Contact');

        if($contact) {
            return $contact;
        }

        return false;
    }

    public function getFiles()
    {
        return (new Files())->where('type', 'teacher')->where('uid', $this->attributes['id'])->findAll();
    }
}
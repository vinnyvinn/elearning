<?php


namespace App\Entities;


use App\Models\Students;
use CodeIgniter\Entity;

class Parents extends Entity
{
    public function getAvatar() {
        if( isset($this->attributes['avatar']) && @$this->attributes['avatar'] != '') {
            return base_url('uploads/avatars/'.$this->attributes['avatar']);
        } else {
            return base_url('assets/img/default.jpg');
        }
        return null;
    }

    public function getProfile()
    {
        return (new \App\Models\User())->find($this->attributes['id']);
    }

    public function getName()
    {
        return $this->attributes['surname'].' '.$this->attributes['first_name'].' '.$this->attributes['last_name'];
    }

    public function getStudents()
    {
        return (new Students())->where('parent', $this->attributes['id'])
            ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id')
            ->where('class !=',null)
            ->where("students.session",active_session())
            ->orderBy('users.surname, users.first_name,users.last_name')
            ->findAll();
    }

    public function getStudentsCurrent()
    {
        return (new Students())->where('parent', $this->attributes['id'])->where('session',active_session())->where('students.active',1)
            ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id')
            ->where('class !=',null)
            ->where("students.session",active_session())
            ->orderBy('users.surname, users.first_name,users.last_name')
            ->findAll();
    }
    public function getEvents()
    {
        $students = $this->getStudents();
        $events = [];
        foreach ($students as $student) {
            $events = array_merge($events, $student->events);
        }

        $ev = [];
        foreach ($events as $event) {
            if(!isset($ev[$event->id])) {
                $ev[$event->id] = $event;
            }
        }

        return $ev;
    }

    public function calendarEvents()
    {
        $events = $this->getEvents();
        $cal = [];
        $n = 0;
        foreach ($events as $event) {
            $n++;
            $t = [
                'id' => $n,
                'title' => $event->name,
                'class' => $event->class ? $event->class->name : 'All classes',
                'section' => $event->section ? $event->section->name : 'All Sections',
            ];
            $t['start'] = $event->starting_date->format('Y-m-d H:i:s');
            if (!$event->ending_date || $event->ending_date == '' || $event->ending_date == $event->starting_date) {
                $t['allDay'] = true;
                //$t['end'] = $event->starting_date->format('Y-m-d H:i:s');
            } else {
                //$t['end'] = $event->ending_date->addHours(24)->format('Y-m-d H:i:s');
                $t['end'] = $event->ending_date->format('Y-m-d H:i:s');
            }

//            if ($event->section && $event->section != '') {
//                $t['className'] = $event->className;
//            } elseif ($event->class && $event->class != '') {
//                $t['className'] = 'bg-primary';
//            } else {
//                $t['className'] = 'bg-danger';
//            }
            $t['className'] = $event->className;
            $cal[] = $t;
        }

        return $cal;
    }

    public function getMessages($teacher)
    {

    }
}
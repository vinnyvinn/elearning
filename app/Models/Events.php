<?php


namespace App\Models;


use CodeIgniter\Model;

class Events extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'starting_date', 'ending_date', 'session', 'class', 'section', 'public', 'image', 'description','className'];

    protected $returnType = '\App\Entities\Event';

    public function calendarEvents()
    {
        $events = $this->where('session',active_session())->orderBy('starting_date')->findAll();
        $cal = [];
        $n = 0;
        foreach ($events as $event) {
            $n++;
            $t = [
                'id' => $n,
                'title' => $event->name.'-'.$event->starting_date->format('Y'),
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
            $t['className'] = $event->className;

            $cal[] = $t;
        }

        return $cal;
    }
    public function calendarEventsCurrentMonth()
    {


        $events = (new Events())
            ->groupStart()
            ->where("MONTH(ending_date) =",date('m'))
            ->orWhere("MONTH(starting_date) =",date('m'))
            ->groupEnd()
            ->where('session',active_session())
            ->orderBy('starting_date', 'ASC')->findAll();

      // $events = (new Events())->where('session',active_session())->where("MONTH(starting_date)",date('m'))->where('YEAR(starting_date)', date('Y'))->orderBy('starting_date',"ASC")->findAll();
       return $events;
    }
}
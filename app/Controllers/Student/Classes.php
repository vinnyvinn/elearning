<?php


namespace App\Controllers\Student;


use App\Controllers\StudentController;
use App\Models\Notes;

class Classes extends StudentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function timetable()
    {
        return $this->_renderPage('Class/timetable', $this->data);
    }

    public function asp()
    {
        return $this->_renderPage('Class/asp', $this->data);
    }

    public function notes()
    {
        return $this->_renderPage('Class/notes', $this->data);
    }

    public function subjects()
    {
        return $this->_renderPage('Class/subjects', $this->data);
    }

    public function download_notes($id)
    {
        $note = (new Notes())->find($id);
        if($note && file_exists($note->path)) {
            return $this->response->download($note->path, null, true);
        }

        return redirect()->back()->with('error', "File not found");
    }
}
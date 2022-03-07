<?php


namespace App\Controllers\Student;


use App\Controllers\StudentController;

class Accounting extends StudentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        return $this->_renderPage('Accounting/index', $this->data);
    }

    public function log()
    {
        return $this->_renderPage('Accounting/log', $this->data);
    }
}
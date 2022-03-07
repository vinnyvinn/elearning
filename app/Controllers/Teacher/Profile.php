<?php


namespace App\Controllers\Teacher;


use App\Controllers\TeacherController;
use App\Entities\Requirement;
use App\Models\PaymentSubmission;
use App\Models\Requirements;
use App\Models\RequirementSubmission;
use App\Models\Students;

class Profile extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (isMobile())
            return view('Teacher/Home/index_mobile', $this->data);
        return $this->_renderPage('Home/index', $this->data);
    }
    public function dashboard()
    {
        return $this->_renderPage('Home/index', $this->data);
    }

    public function calendar()
    {
        return $this->_renderPage('Home/calendar', $this->data);
    }

}
<?php namespace App\Controllers;

use App\Libraries\IonAuth;

class Home extends PublicController
{

    public $data;
    private $ionAuth;
    private $ionAuthModel;
    public function __construct()
    {
        $this->ionAuthModel = new \App\Models\IonAuthModel();
        $this->ionAuth = new IonAuth();
        $this->data = [];
    }

    public function index()
	{
	    $this->data['site_title'] = "Home";

		return $this->_renderPage('Pages/index', $this->data);
	}

    public function confirmation()
    {
       log_message('error','confirmation!!');
       log_message("error",print_r($_POST));
    }
    public function validation()
    {
        log_message('error','validation!!');
        log_message("error",print_r($_POST));
    }
    public function timeout()
    {
        log_message('error','timeout!!');
        log_message("error",print_r($_POST));
    }
    public function transactionStatus()
    {
        log_message('error','transaction status!!');
        log_message("error",print_r($_POST));
    }
    public function stkConfirmation()
    {
        log_message('error','confirmation status!!');
        log_message("error",print_r($_POST));
    }
    public function concept()
	{
	    $this->data['site_title'] = "Concept";

		return $this->_renderPage('Pages/concept-home', $this->data);
	}
    public function contactUs()
    {
        $this->data['site_title'] = "Contact Us";

        return $this->_renderPage('Pages/contact_us', $this->data);
	}

    public function noticeBoard()
    {
        $this->data['site_title'] = "Notice Board";

        return $this->_renderPage('Pages/notice_board', $this->data);
	}
}

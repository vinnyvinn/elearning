<?php


namespace App\Controllers;


use App\Models\User;
use Config\App;

class AdminController extends BaseController
{
    public $data;

    public function __construct()
    {
        $this->data['user'] = (new User())->find((new \App\Libraries\IonAuth())->user()->row()->id);
    }

    public function _renderPage($view, $data = [], $return = false) {
        $html = view($view, $data);
        $this->data['_content'] = $html;

        $content = view('Admin/layout', $this->data);

        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }

    public function _renderPageCustom($view, $data = [], $return = false) {
        $html = view($view, $data);
        $this->data['_content'] = $html;

        $content = view('Admin/layout_custom', $this->data);

        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }
}
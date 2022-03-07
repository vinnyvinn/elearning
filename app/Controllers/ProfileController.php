<?php


namespace App\Controllers;


use App\Libraries\IonAuth;
use App\Models\User;
use Config\Services;

class ProfileController extends BaseController
{
    public $data;

    public function __construct()
    {
        $this->data['user'] = (new User())->find((new \App\Libraries\IonAuth())->user()->row()->id);
    }

    /**
     * Render view
     *
     * @param $view
     * @param array $data
     * @param bool $return
     * @return string
     */
    public function _renderPage($view, $data = [], $return = false) {
        $data = array_merge($data, $this->data);
        $html = view($view, $data);
        $data['_content'] = $html;

        //if((new IonAuth())->inGroup([1])) {
            $content = view('Admin/layout', $data);
        //} else {
            //$content = view('Teacher/layout', $data);
        //}


        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }
    public function _renderPageCustom($view, $data = [], $return = false) {
        $data = array_merge($data, $this->data);
        $html = view($view, $data);
        $data['_content'] = $html;

        //if((new IonAuth())->inGroup([1])) {
            $content = view('Admin/layout_custom', $data);
        //} else {
            //$content = view('Teacher/layout', $data);
        //}


        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }
}
<?php


namespace App\Controllers;


use App\Models\Sessions;
use App\Models\Students;
use App\Models\Teachers;
use App\Models\User;

class TeacherController extends BaseController
{
    public $data;

    public function __construct()
    {
        $user = (new User())->find((new \App\Libraries\IonAuth())->user()->row()->id);
        $this->data['user'] = $user;
        $sess = (new Sessions())->where('active',1)->get()->getRow()->id;
        $this->teacher = (new Teachers())->where('user_id', $user->id)->where('session',$sess)->get()->getLastRow('App\Entities\Teacher');
        $this->data['teacher'] = $this->teacher;
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
        $html = view('Teacher/'.$view, $data);
        $data['_content'] = $html;

        $content = view('Teacher/layout', $data);

        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }
}
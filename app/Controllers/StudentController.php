<?php


namespace App\Controllers;


use App\Models\Sessions;
use App\Models\Students;
use App\Models\User;

class StudentController extends BaseController
{
    public $data;
    public $student;

    public function __construct()
    {
        $user = (new User())->find((new \App\Libraries\IonAuth())->user()->row()->id);
        $this->data['user'] = $user;
        $sess = (new Sessions())->where('active',1)->get()->getRow()->id;
        $this->data['student'] = (new Students())->where('user_id', $user->id)->where('session',$sess)->get()->getLastRow('App\Entities\Student');
        $this->student = $this->data['student'];
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
        $html = view('Student/'.$view, $data);
        $data['_content'] = $html;

        $content = view('Student/layout', $data);

        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }
}
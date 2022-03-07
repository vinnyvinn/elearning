<?php


namespace App\Controllers;


use App\Models\Parents;
use App\Models\Students;
use App\Models\User;

class ParentController extends BaseController
{
    public $data;

    public function __construct()
    {
        $user = (new User())->find((new \App\Libraries\IonAuth())->user()->row()->id);
        $this->data['user'] = $user;
        $this->parent = (new Parents())->where('id', $user->id)->get()->getLastRow('App\Entities\Parents');
        $this->data['parent'] = $this->parent;
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

        $html = view('Parent/'.$view, $data);
        $data['_content'] = $html;

        $content = view('Parent/layout', $data);

        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }
}
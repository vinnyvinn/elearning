<?php


namespace App\Controllers;


class PublicController extends BaseController
{

    public function _AuthRenderPage($view, $data = [], $return = false) {
        $html = view($view, $data);
        $data['_content'] = $html;

        $content = view('Auth/layout', $data);

        if($return) {
            return $content;
        } else {
            echo $content;
        }
    }

    public function _renderPage($view, $data = [])
    {
        $data = array_merge($this->data, $data);
        $html = view('Frontend/'.$view, $data);

        $x['_content'] = $html;

//        return view('Frontend/layout', $x);
        return view('Frontend/concept-layout', $x);
    }
}
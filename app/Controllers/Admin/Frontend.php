<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;
use App\Entities\Event;
use App\Entities\File;
use App\Models\Events;
use App\Models\Files;
use App\Models\Notices;
use App\Models\Slides;
use Config\Services;

class Frontend extends AdminController
{
    public $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = Services::session();
    }

    public function index()
    {

        $this->data['site_title'] = "Front End Settings";

        return $this->_renderPage('Admin/Frontend/index', $this->data);
    }

    public function noticeBoardNew()
    {

        $this->data['site_title'] = "Front End Settings";

        return $this->_renderPage('Admin/Frontend/notice_new', $this->data);
    }
    public function noticeBoardEdit($id)
    {

        $this->data['site_title'] = "Front End Settings";
        $this->data['event'] = (new Notices())->find($id);

        return $this->_renderPage('Admin/Frontend/notice_edit', $this->data);
    }

    public function removePicture()
    {
      $data = array();
      $pics = json_decode(get_option("website_pictures"));
      foreach ($pics as $pic){
          if ($pic !== $this->request->getPost('picture')){
              array_push($data,$pic);
          }
      }
      update_option("website_pictures",json_encode($data));
        $msg = "Removed successfully";
        $status = 'success';

        $this->session->setFlashData($status, $msg);
       // return $this->response->redirect(previous_url());
    }
    public function removeInfoPicture()
    {
      $data = array();
      $pics = json_decode(get_option("teacher_information_file"));
      foreach ($pics as $pic){
          if ($pic !== $this->request->getPost('picture')){
              array_push($data,$pic);
          }
      }
      update_option("teacher_information_file",json_encode($data));
        $msg = "Removed successfully";
        $status = 'success';

        $this->session->setFlashData($status, $msg);
       // return $this->response->redirect(previous_url());
    }
    public function removeAdminPicture()
    {
      $data = array();
      $pics = json_decode(get_option("admin_doc"));
      foreach ($pics as $pic){
          if ($pic !== $this->request->getPost('picture')){
              array_push($data,$pic);
          }
      }
      update_option("admin_doc",json_encode($data));
        $msg = "Removed successfully";
        $status = 'success';

        $this->session->setFlashData($status, $msg);
       // return $this->response->redirect(previous_url());
    }
    public function removeTeacherPicture()
    {
      $data = array();
      $pics = json_decode(get_option("teacher_description_file"));
      foreach ($pics as $pic){
          if ($pic !== $this->request->getPost('picture')){
              array_push($data,$pic);
          }
      }
      update_option("teacher_description_file",json_encode($data));
        $msg = "Removed successfully";
        $status = 'success';

        $this->session->setFlashData($status, $msg);
       // return $this->response->redirect(previous_url());
    }
    public function removeStudentDoc()
    {
      $data = array();
      $pics = json_decode(get_option("student_doc"));
      foreach ($pics as $pic){
          if ($pic !== $this->request->getPost('doc')){
              array_push($data,$pic);
          }
      }
      update_option("student_doc",json_encode($data));
        $msg = "Removed successfully";
        $status = 'success';

        $this->session->setFlashData($status, $msg);
       // return $this->response->redirect(previous_url());
    }
    public function removeLogo()
    {
      update_option("website_logo","");
        $msg = "Removed successfully";
        $status = 'success';

        $this->session->setFlashData($status, $msg);
       // return $this->response->redirect(previous_url());
    }
    public function saveGeneral()
    {

        if ($docs = $this->request->getFiles()) {

            $files = array();
            if (!empty($docs['website_pictures'])) {
                foreach ($docs['website_pictures'] as $pic) {
                    //  var_dump($pic->isValid());
                    //  exit();
                    if ($pic->isValid() && !$pic->hasMoved()) {
                        $newName = $pic->getRandomName();
                        if ($pic->move(FCPATH . 'uploads/files/', $newName)) {
                            array_push($files, $newName);
                        }
                    }
                }
                if (!empty($files))
                    update_option('website_pictures', json_encode($files));
            }

            $admin_files_ = array();
            if (!empty($docs['admin_doc'])) {
                foreach ($docs['admin_doc'] as $pic) {
                            if ($pic->isValid() && !$pic->hasMoved()) {
                                $newName = $pic->getRandomName();
                                if ($pic->move(FCPATH . 'uploads/files/', $newName)) {
                                    array_push($admin_files_, $newName);
                                }
                            }
                }
                if (!empty($admin_files_))
                    update_option('admin_doc', json_encode($admin_files_));
            }

            if ($logo = $docs['website_logo']) {
                if ($logo->isValid() && !$logo->hasMoved()) {
                    $newName = $logo->getRandomName();
                    if ($logo->move(FCPATH . 'uploads/files/', $newName)) {
                        update_option('website_logo', $newName);
                    }
                }
            }

            $files = array();
            if ($docs['description_file'] !=null) {
                foreach ($docs['description_file'] as $pic) {
                    if ($pic->isValid() && !$pic->hasMoved()) {
                        $newName = $pic->getRandomName();
                        if ($pic->move(FCPATH . 'uploads/files/', $newName)) {
                            array_push($files, $newName);
                        }
                    }
                }
                if (!empty($files))
                    update_option('teacher_description_file', json_encode($files));
            }

            $std_files = array();
            if ($docs['student_doc'] !=null) {
                foreach ($docs['student_doc'] as $pic) {
                    if ($pic->isValid() && !$pic->hasMoved()) {
                        $newName = $pic->getRandomName();
                        if ($pic->move(FCPATH . 'uploads/files/', $newName)) {
                            array_push($std_files, $newName);
                        }
                    }
                }
                if (!empty($std_files))
                    update_option('student_doc', json_encode($std_files));
            }


            $files = array();
            if ($docs['information_file'] !=null) {
                foreach ($docs['information_file'] as $pic) {
                    if ($pic->isValid() && !$pic->hasMoved()) {
                        $newName = $pic->getRandomName();
                        if ($pic->move(FCPATH . 'uploads/files/', $newName)) {
                            array_push($files, $newName);
                        }
                    }
                }
                if (!empty($files))
                    update_option('teacher_information_file', json_encode($files));
            }

            if ($logo = $docs['logo1']) {
                if ($logo->isValid() && !$logo->hasMoved()) {
                    $newName = $logo->getRandomName();
                    if ($logo->move(FCPATH . 'uploads/files/', $newName)) {
                        update_option('logo1', $newName);
                    }
                }
            }
            if ($logo2 = $docs['logo2']) {
                if ($logo2->isValid() && !$logo2->hasMoved()) {
                    $newName = $logo2->getRandomName();
                    if ($logo2->move(FCPATH . 'uploads/files/', $newName)) {
                        update_option('logo2', $newName);
                    }
                }
            }
        }

        update_option('website_phone', json_encode($this->request->getPost('website_phone')));
        update_option('student_phone', $this->request->getPost('student_phone') ? json_encode($this->request->getPost('student_phone')) : '');
        update_option('teacher_phone', $this->request->getPost('teacher_phone') ? json_encode($this->request->getPost('teacher_phone')) : '');
        update_option('admin_phone', $this->request->getPost('admin_phone') ? json_encode($this->request->getPost('admin_phone')) : '');
        update_option('phone1', $_POST['phone1'] ? json_encode($_POST['phone1']) : '');
        update_option('phone2', $_POST['phone2'] ? json_encode($_POST['phone2']) : '');
        update_option('website_location', esc($this->request->getPost('website_location')));
        update_option('address1', esc($this->request->getPost('address1')));
        update_option('address2', esc($this->request->getPost('address2')));
        update_option('welcome_message', esc($this->request->getPost('welcome_message')));
        update_option('student_description', esc($this->request->getPost('student_description')));
        update_option('map_address', esc($this->request->getPost('map_address')));
        update_option('facebook_link', esc($this->request->getPost('facebook_link')));
        update_option('twitter_link', esc($this->request->getPost('twitter_link')));
        update_option('telegram_link', esc($this->request->getPost('telegram_link')));
        update_option('telegram_url', esc($this->request->getPost('telegram_url')));
        update_option('youtube_link', esc($this->request->getPost('youtube_link')));
        update_option('website_link', esc($this->request->getPost('website_link')));
        update_option('website_terms', $this->request->getPost('website_term') ? json_encode($this->request->getPost('website_term')):'');

        $return = TRUE;
        $msg = "Recorded successfully";
        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {

            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'toastr',
                'callback'  => 'window.location.reload()'
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function editNotice($id)
    {
        if ($this->request->getPost()) {
            $to_db = [
                'date_created' => $this->request->getPost('date'),
                'info' => $this->request->getPost('description'),
                'public' => 1,
                'title' => $this->request->getPost('title')
            ];

            $images = array();
            if ($files = $this->request->getFiles()) {
                foreach ($files['image'] as $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $name = $img->getRandomName();
                        if ($img->move(FCPATH . 'uploads', $name)) {
                            array_push($images, $name);
                        }
                    }
                }
                $to_db['image'] = json_encode($images);
            }

            $model = new Notices();
            try {
                $db = \Config\Database::connect();
                $builder = $db->table('notices');
                $builder->where('id', $id);
                if ($builder->update($to_db)) {
                    $images ? update_option('notice_' .$id, json_encode($images)) : '';
                    $return = TRUE;
                    $msg = "Notice updated successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to update notice";
                }
            } catch (\ReflectionException $e) {
                $return = FALSE;
                $msg = $e->getMessage();
            }
            $status = $return ? 'success' : 'error';
            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $status,
                    'message' => $msg,
                    'title' => $return ? 'Success' : 'Error',
                    'callback' => $return ? 'window.location.reload()' : '',
                ];

                return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
            }

            return redirect()->to(previous_url())->with($status, $msg);

        }
    }

    public function homeSettings()
    {
        $this->data['site_title'] = "Home Page Settings";

        return $this->_renderPage('Admin/Frontend/homepage', $this->data);
    }

    public function editHomeSettings()
    {
        $video = $_GET['video'];
        $videos = json_decode(get_option('home_videos'));
        $detail = array();
        foreach ($videos as $v){
            if ($v->video == $video){
                $detail = array('video'=>$video,'title'=>$v->title,'description'=>$v->description);
            }
        }

        $this->data['site_title'] = "Home Page Settings";
        $this->data['detail'] = $detail;

        return $this->_renderPage('Admin/Frontend/edit_homepage', $this->data);
    }
    public function homeIndex()
    {
        $this->data['site_title'] = "Home Page Settings";

        return $this->_renderPage('Admin/Frontend/homepage_index', $this->data);
    }
    public function missionSettings()
    {
        $this->data['site_title'] = "Home Page Settings";

        return $this->_renderPage('Admin/Frontend/mission', $this->data);
    }

    public function saveMission()
    {
        $mission_statement_file = $this->request->getFile('mission_statement_file');
        $vision_statement_file = $this->request->getFile('vision_statement_file');
        $goal_statement_file = $this->request->getFile('goal_statement_file');
        $footer_file = $this->request->getFile('footer_file');

        if (isset($mission_statement_file))
        $mission_name = $mission_statement_file->getRandomName();
        if (isset($vision_statement_file))
        $vision_name = $vision_statement_file->getRandomName();
        if (isset($goal_statement_file))
        $goal_name = $goal_statement_file->getRandomName();
        if (isset($footer_file))
        $footer_name = $footer_file->getRandomName();

        if(isset($mission_statement_file) && $mission_statement_file->isValid() && $mission_statement_file->move(FCPATH.'uploads', $mission_name)) {
            update_option('mission_statement_file', $mission_name);
        }
        if(isset($vision_statement_file) && $vision_statement_file->isValid() &&  $vision_statement_file->move(FCPATH.'uploads', $vision_name)) {
            update_option('vision_statement_file', $vision_name);
        }
        if(isset($goal_statement_file) &&  $goal_statement_file->isValid() && $goal_statement_file->move(FCPATH.'uploads', $goal_name)) {
            update_option('goal_statement_file', $goal_name);
        }
        if(isset($footer_file) && $footer_file->isValid() &&  $footer_file->move(FCPATH.'uploads', $footer_name)) {
            update_option('footer_file', $footer_name);
        }

        $footer_items = array();
          foreach ($this->request->getPost('footer_number') as $key => $footer){
              foreach ($this->request->getPost('footer_description') as $k => $v){
             if ($key == $k){
               array_push($footer_items,array('number'=>$footer,'description'=>$v));
             }
              }
          }
          if (!empty($footer_items))
           update_option('footer_items', json_encode($footer_items));
           update_option('mission_statement', $this->request->getPost('mission_statement'));
           update_option('vision_statement', $this->request->getPost('vision_statement'));
           update_option('goal_statement', $this->request->getPost('goal_statement'));

            $return = TRUE;
            $msg = "Recorded successfully";
            $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'toastr',
                'callback'  => $return ? 'window.location.reload()' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }
    public function saveSlides()
    {
        $docs = $this->request->getFiles();

        $files = array();
        if ($docs['home_video'] !=null) {
            foreach ($docs['home_video'] as $k => $vid) {
                foreach ($this->request->getPost('video_title') as $k_t => $t) {
                    foreach ($this->request->getPost('video_description') as $k_d => $d) {
                        if ($k == $k_t && $k == $k_d) {
                            if ($vid->isValid() && !$vid->hasMoved()) {
                                $newName = $vid->getRandomName();
                                if ($vid->move(FCPATH . 'uploads/files/', $newName)) {
                                    array_push($files, array('video'=>$newName,'title'=>$t,'description'=>$d));
                                }
                            }
                        }
                    }
                }
            }
        }

        $stored_videos = get_option('home_videos') ? json_decode(get_option('home_videos')) : '';
        $data_arr = array();
        $final_arr = $files;

        if (!empty($stored_videos)){
            foreach ($stored_videos as $vid){
                array_push($data_arr,array('video'=>$vid->video,'title'=>$vid->title,'description'=>$vid->description));
            }
        }
        if (!empty($data_arr)){
            $final_arr =  array_merge($files,$data_arr);
        }

        update_option('home_videos', json_encode($final_arr));


            $return = TRUE;
            $msg = "Recorded successfully";
            $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'toastr',
                'callback'  => $return ? 'window.location.reload()' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

  public function updateSlides()
    {

        $video = $this->request->getPost('video');
        $videos = json_decode(get_option('home_videos'));
        //var_dump($videos);
      //  exit();
        $detail = array();
        $data = array();
        foreach ($videos as $v){
            if ($v->video == $video){
                $detail = array('video'=>$video,'title'=> $this->request->getPost('video_title'),'description'=> $this->request->getPost('video_description'));

                if ($vid = $this->request->getFile('home_video')){
                    if ($vid->isValid() && !$vid->hasMoved()) {
                        $newName = $vid->getRandomName();
                        if ($vid->move(FCPATH . 'uploads/files/', $newName)) {
                            $detail['video'] = $newName;
                        }
                    }
                }
                array_push($data,$detail);
            }else {
                array_push($data,array('video'=>$v->video,'title'=>$v->title,'description'=>$v->description));
            }

        }

        update_option('home_videos', json_encode($data));

            $return = TRUE;
            $msg = "Recorded successfully";
            $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'toastr',
                'callback'  => $return ? 'window.location.reload()' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function deleteSlide($id)
    {
        $slidesModel = new Slides();
        $slide = $slidesModel->find($id);
        if (!$slide) {
            return redirect()->back()->with('error', "Slide not found");
        }

        if ($slidesModel->delete($id)) {
            @unlink($slide->path);
            return redirect()->back()->with('success', "Slide deleted successfully");
        }

        return redirect()->back()->with('error', "Could not delete slide");
    }

    public function deleteVideo($id)
    {
        $id = $id-1;
        $videos = get_option('home_videos') ? json_decode(get_option('home_videos')) : '';
        $new_videos = $videos;
        $data = array();

        foreach ($videos as $key => $vid){
          if ($key == $id){
              unset($new_videos[$id]);
          }
        }
        if (!empty($new_videos)){
            foreach ($new_videos as $vid){
                array_push($data,array('title'=>$vid->title,'description'=>$vid->description,'video'=>$vid->video));
            }
            update_option('home_videos',json_encode($data));
            return redirect()->back()->with('success', "Video deleted successfully");
        }else{
            update_option('home_videos','');
            return redirect()->back()->with('success', "Video deleted successfully");
        }
        return redirect()->back()->with('success', "Video deleted successfully");
    }

    public function events()
    {
        $this->data['site_title'] = "Homepage Events";

        return $this->_renderPage('Admin/Frontend/events', $this->data);
    }

    public function saveEvents()
    {
        if($this->request->getPost()) {
            $to_db = [
                'name'      => $this->request->getPost('title'),
                'starting_date' => $this->request->getPost('date'),
                'description'   => $this->request->getPost('description'),
                'session'   => active_session(),
                'public'        => 1
            ];
            $model = new Events();
            try {
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Event saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "A database error occured";
                }
            } catch (\ReflectionException $e) {
                $return = FALSE;
                $msg = $e->getMessage();
            }

            $status = $return ? 'success' : 'error';
            if($this->request->isAJAX()) {

                $resp = [
                    'title'     => $return ? 'Success' : 'Error',
                    'message'   => $msg,
                    'status'    => $status,
                    'notifyType'    => 'toastr',
                    'callback'  => $return ? 'window.location.reload()' : ''
                ];

                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            }

            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(previous_url());
        }

        return $this->response->setStatusCode(404)->setBody('Invalid request');
    }

    public function updateEvents($id)
    {
        if($this->request->getPost()) {
            $to_db = [
                'name'      => $this->request->getPost('title'),
                'starting_date' => $this->request->getPost('date'),
                'description'   => $this->request->getPost('description'),
                'public'        => 1
            ];
            $model = new Events();
            try {
                $to_db['id'] = $id;
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Event updated successfully";
                } else {
                    $return = FALSE;
                    $msg = "A database error occurred";
                }
            } catch (\ReflectionException $e) {
                $return = FALSE;
                $msg = $e->getMessage();
            }

            $status = $return ? 'success' : 'error';
            if($this->request->isAJAX()) {

                $resp = [
                    'title'     => $return ? 'Success' : 'Error',
                    'message'   => $msg,
                    'status'    => $status,
                    'notifyType'    => 'toastr',
                    'callback'  => $return ? 'window.location.reload()' : ''
                ];

                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            }

            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(previous_url());
        }

        return $this->response->setStatusCode(404)->setBody('Invalid request');
    }
    public function noticeBoard()
    {
        $this->data['site_title'] = "Notice Board";

        return $this->_renderPage('Admin/Frontend/notice_board', $this->data);
    }

    public function saveNotice()
    {
        if($this->request->getPost()) {
            $to_db = [
                'date_created'  => $this->request->getPost('date'),
                'info'          => $this->request->getPost('description'),
                'public'        => 1,
                'title'         => $this->request->getPost('title')
            ];

            $images = array();
            if($files = $this->request->getFiles()) {
                foreach ($files['image'] as $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $name = $img->getRandomName();
                        if ($img->move(FCPATH . 'uploads', $name)) {
                            array_push($images,$name);
                        }
                    }
                }
               $to_db['image'] = json_encode($images);
            }

            $model = new Notices();
            $to_db['session'] = active_session();
            try {
                if ($model->save($to_db)) {
                    $images ? update_option('notice_'. $model->getInsertID(),json_encode($images)) : '';
                    $return = TRUE;
                    $msg = "Notice posted successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save notice";
                }
            } catch (\ReflectionException $e) {
                $return = FALSE;
                $msg = $e->getMessage();
            }
            $status = $return ? 'success' : 'error';
            if($this->request->isAJAX()) {
                $resp = [
                    'status'    => $status,
                    'message'   => $msg,
                    'title'     => $return ? 'Success' : 'Error',
                    'callback'  => $return  ? 'window.location.reload()' : '',
                ];

                return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
            }

            return redirect()->to(previous_url())->with($status, $msg);
        }

        if($this->request->isAJAX()) {
            return $this->response->setBody("Invalid request")->setStatusCode(404);
        }

        return redirect()->to(previous_url())->with('error', "Invalid request");
    }

    public function deleteNotice($id)
    {
        $model = new Notices();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Notice deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete the notice";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return  ? 'window.location.reload()' : '',
            ];

            return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
        }

        return redirect()->to(previous_url())->with($status, $msg);
    }
    public function hideNotice($id)
    {
        $model = new Notices();
        $data = array('active'=>0);
        $db = \Config\Database::connect();
        $builder = $db->table('notices');
        $builder->where('id',$id);
        if($builder->update($data)) {
            $return = TRUE;
            $msg = "Notice hidden successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to hide the notice";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return  ? 'window.location.reload()' : '',
            ];

            return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
        }

        return redirect()->to(previous_url())->with($status, $msg);
    }

    public function showNotice($id)
    {
        $model = new Notices();
        $data = array('active'=>1);
        $db = \Config\Database::connect();
        $builder = $db->table('notices');
        $builder->where('id',$id);
        if($builder->update($data)) {
            $return = TRUE;
            $msg = "Notice shown successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to show the notice";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return  ? 'window.location.reload()' : '',
            ];

            return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
        }

        return redirect()->to(previous_url())->with($status, $msg);
    }

    public function deleteEvent($id)
    {
        $model = new Events();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Notice deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete the event";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return  ? 'window.location.reload()' : '',
            ];

            return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
        }

        return redirect()->to(previous_url())->with($status, $msg);
    }
}
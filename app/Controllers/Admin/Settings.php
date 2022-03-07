<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;
use App\Models\AnswerOption;
use App\Models\Evaluations;
use App\Models\KGCategory;
use App\Models\KGEvaluation;
use App\Models\KGSubCategory;
use App\Models\StudentComment;
use CodeIgniter\Model;

class Settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_renderPage('Admin/Settings/index');
    }
    public function evaluation()
    {
        $this->_renderPage('Admin/Settings/evaluation_index');
    }
    public function answerOption()
    {
        $this->_renderPage('Admin/Settings/answer_option');
    }
    public function category()
    {
        $this->_renderPage('Admin/Settings/category_index');
    }
    public function sub_category()
    {
        $this->_renderPage('Admin/Settings/sub_category_index');
    }
    public function kg_evaluation()
    {
        $this->_renderPage('Admin/Settings/kg_index');
    }
    public function studentID()
    {
        $this->_renderPage('Admin/Settings/id_card');
    }
    public function grading()
    {
        $this->_renderPage('Admin/Settings/grading');
    }
    public function promotionRule()
    {
        $this->_renderPage('Admin/Settings/promotion_rule');
    }
    public function background()
    {
        $file = $this->request->getFile('student_background_image');
        if (isset($file)){
            $name = $file->getRandomName();

            if($file->move(FCPATH.'uploads', $name)) {
                update_option('student_background_image', $name);
            }
        }
        $this->_renderPage('Admin/Settings/background_image');
    }

    public function savePromotionRule()
    {
        update_option('promotion_pass_mark',$this->request->getPost('promotion_pass_mark'));
        update_option('no_subject_failed',$this->request->getPost('no_subject_failed'));
        $status = 'success';
        $return =   true;
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => 'Promotion Rule saved successfully',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return  ? 'window.location.reload()' : '',
            ];

            return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
        }
    }

    public function examsNo()
    {
        $this->_renderPage('Admin/Settings/exams_no');
    }

    public function saveExamsNo()
    {
        update_option('no_of_exams',$this->request->getPost('no_of_exams'));
        $status = 'success';
        $return =   true;
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => 'Exams No saved successfully',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return  ? 'window.location.reload()' : '',
            ];

            return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
        }
    }
    public function comment()
    {
        $this->_renderPage('Admin/Settings/comment_index');
    }
    public function save_comment()
    {
        if($this->request->getPost()) {
            $to_db = [
                'description' => $this->request->getPost('description')
            ];

            $model = new StudentComment();
            try {
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Comment saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save comment";
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

    public function save_id()
    {
        $resp = [
            'status'    => 'error',
            'message'   => 'classes must be unique',
            'title'     => 'Error',
            'callback'  => '',
        ];

        foreach ($this->request->getPost('id_kg_class') as $class){
            if (in_array($class,$this->request->getPost('id_hs_class')) || in_array($class,$this->request->getPost('id_el_class'))){
                return $this->response->setBody(json_encode($resp))->setContentType('application/json');
            }
        }
        foreach ($this->request->getPost('id_el_class') as $class){
            if (in_array($class,$this->request->getPost('id_kg_class')) || in_array($class,$this->request->getPost('id_hs_class'))){
                return $this->response->setBody(json_encode($resp))->setContentType('application/json');
            }
        }
        foreach ($this->request->getPost('id_hs_class') as $class){
            if (in_array($class,$this->request->getPost('id_kg_class')) || in_array($class,$this->request->getPost('id_el_class'))){
                return $this->response->setBody(json_encode($resp))->setContentType('application/json');
            }
        }
        update_option('id_school_name', $this->request->getPost('id_school_name'));
        update_option('id_school_name_amharic', $this->request->getPost('id_school_name_amharic'));
        update_option('id_grade_range', $this->request->getPost('id_grade_range'));
        update_option('id_location', $this->request->getPost('id_location'));
        update_option('id_woreda', $this->request->getPost('id_woreda'));
        update_option('id_kg_class', $this->request->getPost('id_kg_class') ? json_encode($this->request->getPost('id_kg_class')) : '');
        update_option('id_kg_phone', $this->request->getPost('id_kg_phone') ? json_encode($this->request->getPost('id_kg_phone')) : '');
        update_option('id_el_class', $this->request->getPost('id_el_class') ? json_encode($this->request->getPost('id_el_class')) : '');
        update_option('id_el_phone', $this->request->getPost('id_el_phone') ? json_encode($this->request->getPost('id_el_phone')) : '');
        update_option('id_hs_class', $this->request->getPost('id_hs_class') ? json_encode($this->request->getPost('id_hs_class')) : '');
        update_option('id_hs_phone', $this->request->getPost('id_hs_phone') ? json_encode($this->request->getPost('id_hs_phone')) : '');
        update_option('id_text', $this->request->getPost('id_text') ? json_encode($this->request->getPost('id_text')) : '');
        update_option('id_date_issued_label', esc($this->request->getPost('id_date_issued_label')));
        update_option('id_date_issued', esc($this->request->getPost('id_date_issued')));
        update_option('id_expiry_date_label', esc($this->request->getPost('id_expiry_date_label')));
        update_option('id_expiry_date', esc($this->request->getPost('id_expiry_date')));
        update_option('id_parent', esc($this->request->getPost('id_parent')));
        update_option('id_address', esc($this->request->getPost('id_address')));
        update_option('id_subcity', esc($this->request->getPost('id_subcity')));
        update_option('id_house_no', esc($this->request->getPost('id_house_no')));
        update_option('id_phone1', esc($this->request->getPost('id_phone1')));
        update_option('id_phone2', esc($this->request->getPost('id_phone2')));
        update_option('id_header', esc($this->request->getPost('id_header')));
        update_option('id_sign', esc($this->request->getPost('id_sign')));
        update_option('id_autofill', esc($this->request->getPost('id_autofill')));
        update_option('id_show_date_issued', esc($this->request->getPost('id_show_date_issued')));
        update_option('id_show_expiry_date', esc($this->request->getPost('id_show_expiry_date')));
        return redirect()->to(previous_url())->with('success', "Updated Successfully");

    }

    public function saveGrading()
    {
        update_option("grade_a",$this->request->getPost("grade_a"));
        update_option("grade_b",$this->request->getPost("grade_b"));
        update_option("grade_c",$this->request->getPost("grade_c"));
        update_option("grade_d",$this->request->getPost("grade_d"));
        update_option("grade_f",$this->request->getPost("grade_f"));
        return redirect()->to(previous_url())->with('success', "Updated Successfully");
    }
    public function save_evaluation()
    {
        if($this->request->getPost()) {
            $to_db = [
                'description' => $this->request->getPost('description')
            ];

            $model = new Evaluations();
            try {
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Evaluation saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save evaluation";
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
    public function save_kg_evaluation()
    {
        if($this->request->getPost()) {

            $model = new KGEvaluation();
            try {
                if ($model->save($this->request->getPost())) {
                    $return = TRUE;
                    $msg = "Evaluation saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save evaluation";
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
    public function save_category()
    {
        if($this->request->getPost()) {
           $requests = $this->request->getPost();
           $requests['sub_category_id'] = $this->request->getPost('sub_category_id') ? json_encode($this->request->getPost('sub_category_id')) :'' ;
            $model = new KGCategory();
            try {
                if ($model->save($requests)) {
                    $return = TRUE;
                    $msg = "Category saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save category";
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
    public function save_option()
    {
        if($this->request->getPost()) {
            $requests = $this->request->getPost();
            $model = new AnswerOption();
            try {
                if ($model->save($requests)) {
                    $return = TRUE;
                    $msg = "Option saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save Option";
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
    public function save_sub_category()
    {
        if($this->request->getPost()) {

            $model = new KGSubCategory();
            try {
                if ($model->save($this->request->getPost())) {
                    $return = TRUE;
                    $msg = "Category saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save category";
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
    public function edit_comment($id) {
        $model = new StudentComment();
        if($model->save($this->request->getPost())) {
            $return = true;
            $msg = "Comment updated successfully";
        } else {
            $return = false;
            $msg = implode('<br/>', $model->errors());
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
        }

        return $this->response->redirect(site_url(route_to('admin.settings.student-comment')))->withInput();
    }
    public function edit_evaluation($id) {
        $model = new Evaluations();
        if($model->save($this->request->getPost())) {
            $return = true;
            $msg = "Evaluation updated successfully";
        } else {
            $return = false;
            $msg = implode('<br/>', $model->errors());
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
        }

        return $this->response->redirect(site_url(route_to('admin.settings.student-evaluation')))->withInput();
    }
    public function edit_kg_evaluation($id) {
        $model = new KGEvaluation();
       if($model->save($this->request->getPost())) {
            $return = true;
            $msg = "Evaluation updated successfully";
        } else {
            $return = false;
            $msg = implode('<br/>', $model->errors());
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
        }

        return $this->response->redirect(site_url(route_to('admin.settings.kg-evaluation')))->withInput();
    }

    public function edit_option($id) {
        $model = new AnswerOption();
        if($model->save($this->request->getPost())) {
            $return = true;
            $msg = "Option updated successfully";
        } else {
            $return = false;
            $msg = implode('<br/>', $model->errors());
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
        }

        return $this->response->redirect(site_url(route_to('admin.settings.answer-options')))->withInput();
    }


    public function getSubCategories()
    {
      $cat = (new KGCategory())->find($this->request->getPost('category_id'));
      $sub_cat = $cat['sub_category_id'] ? json_decode($cat['sub_category_id']) : [];
      $data = array();
      if (count($sub_cat)>0){
          foreach ($sub_cat as $cat){
              array_push($data,array('id' =>$cat,'name'=>(new KGSubCategory())->find($cat)['name']));
          }
      }

      return json_encode($data);
    }
    public function edit_kg_category($id) {
        $model = new KGCategory();
        $requests = $this->request->getPost();
        $requests['sub_category_id'] = $this->request->getPost('sub_category_id') ? json_encode($this->request->getPost('sub_category_id')) : '';
        if($model->save($requests)) {
            $return = true;
            $msg = "Category updated successfully";
        } else {
            $return = false;
            $msg = implode('<br/>', $model->errors());
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
        }

        return $this->response->redirect(site_url(route_to('admin.settings.kg-category')))->withInput();
    }
    public function edit_kg_sub_category($id) {
        $model = new KGSubCategory();
        if($model->save($this->request->getPost())) {
            $return = true;
            $msg = "Category updated successfully";
        } else {
            $return = false;
            $msg = implode('<br/>', $model->errors());
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
        }

        return $this->response->redirect(site_url(route_to('admin.settings.kg-category')))->withInput();
    }
    public function deleteComment($id)
    {
        $model = new StudentComment();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Comment deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete the comment";
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

    public function deleteEvaluation($id)
    {
        $model = new Evaluations();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Evaluation deleted successfully";
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
    public function deleteOption($id)
    {
        $model = new AnswerOption();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Option deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete the option";
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
    public function deleteKGEvaluation($id)
    {
        $model = new KGEvaluation();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Evaluation deleted successfully";
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
    public function deleteCategory($id)
    {
        $model = new KGCategory();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Category deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete the category";
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
    public function deleteSubCategory($id)
    {
        $model = new KGSubCategory();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Category deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete the category";
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

    public function site() {
        if($this->request->isAJAX()) {
            update_parent_option('system', 'site_title', $this->request->getPost('site_title'));
            if($file = $this->request->getFile('logo')){
                if($file->isValid()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH.'uploads', $newName);
                    $new_logo = $newName;
                    if($old = get_parent_option('system', 'site_logo', FALSE)) {
                        @unlink(FCPATH.'uploads/'.$old);
                    }

                    update_parent_option('system', 'site_logo', $new_logo);
                }
            }
            update_option('currency', $this->request->getPost('currency'));

            do_action('site_settings_save');
            $resp = [
                'status'    => 'success',
                'title'     => 'Success',
                'message'   => 'Settings updated successfully',
                'notifyType'    => 'toastr',
                'notify'    => true
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            return $this->response->redirect(previous_url());
        }
    }

    public function email() {
        if($this->request->isAJAX()) {
            update_parent_option('system', 'email_name', $this->request->getPost('email_name'));
            update_parent_option('system', 'email_email', $this->request->getPost('email_email'));

            do_action('email_settings_save');
            $resp = [
                'status'    => 'success',
                'title'     => 'Success',
                'message'   => 'Settings updated successfully',
                'notifyType'    => 'toastr',
                'notify'    => true
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            return $this->response->redirect(previous_url());
        }
    }
}
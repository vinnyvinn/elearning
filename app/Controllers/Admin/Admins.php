<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;
use App\Libraries\IonAuth;
use App\Models\Classes;
use App\Models\Sections;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Admins extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Admin/Admins/index', $this->data);
    }

    public function create()
    {
        return $this->_renderPage('Admin/Admins/create', $this->data);
    }

    public function pdf()
    {
      $this->data['admins'] = (new \App\Models\Admins())->findAll();
      return view('Admin/Admins/list/pdf', $this->data);
    }
    public function print()
    {
      $this->data['admins'] = (new \App\Models\Admins())->findAll();
      return view('Admin/Admins/list/print', $this->data);
    }
    function exportExcel()
    {
        $admins = (new \App\Models\Admins())->findAll();

        $file_name = 'Admins List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Admins List";
        $sheet->setCellValue("A1","$title");

        //Merge cells
        $sheet->mergeCells('A1:I1');

        $sheet->getStyle("A1")->applyFromArray(
            array(
                'font'=> array('size'=>24,'bold'=>true)
            )
        );

        //Alignment
        $sheet->getStyle("A1")->getAlignment()->setHorizontal("center");

        //adjust dimensions
        $sheet->getColumnDimension("A")->setWidth(30);
        $sheet->getRowDimension("1")->setRowHeight(120);
        $sheet->getColumnDimension("B")->setWidth(20);
        $sheet->getColumnDimension("C")->setWidth(20);
        $sheet->getColumnDimension("D")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);

        // column headers
        $sheet->setCellValue('A2', 'Name');
        $sheet->setCellValue('B2', 'Phone');
        $sheet->setCellValue('C2', 'Username');
        $sheet->setCellValue('D2', 'E-Mail');
        $sheet->setCellValue('E2', 'Status');

        $count = 3;
        foreach($admins as $row)
        {
            $sheet->setCellValue('A' . $count, $row->name);
            $sheet->setCellValue('B' . $count, $row->phone);
            $sheet->setCellValue('C' . $count, $row->username);
            $sheet->setCellValue('D' . $count, $row->email);
            $sheet->setCellValue('E' . $count, $row->active == 1 ? 'ACTIVE' : 'INACTIVE');
            $count++;
        }

        $writer = new Xlsx($spreadsheet);

        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");

        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

        header('Expires: 0');

        header('Cache-Control: must-revalidate');

        header('Pragma: public');

        header('Content-Length:' . filesize($file_name));

        flush();

        readfile($file_name);

        exit;
    }
    public function edit($id)
    {
        $model = new User();
        $user = $model->find($id);
        if(!$user) {
            return redirect(previous_url());
        }

        $this->data['admin'] = $user;
        return $this->_renderPage('Admin/Admins/edit', $this->data);
    }

    public function save()
    {
        $existing = $this->request->getPost('id');

        $validation = \Config\Services::validation();
        $validation->reset();
        if($existing) {
            $validation->setRule('email', 'Email Address', 'trim|required|valid_email');
            $validation->setRule('username', 'Username', 'trim|required');
            $validation->setRule('surname', 'Surname', 'trim|required');
            $validation->setRule('first_name', 'First Name', 'trim|required');
            $validation->setRule('last_name', 'Last Name', 'trim|required');
            $validation->setRule('phone', 'Phone Number', 'trim|required');
        } else {
            $validation->setRule('email', 'Email Address', 'trim|required|valid_email|is_unique[users.email]');
            $validation->setRule('username', 'Username', 'trim|required|is_unique[users.username]');
            $validation->setRule('surname', 'Surname', 'trim|required');
            $validation->setRule('first_name', 'First Name', 'trim|required');
            $validation->setRule('last_name', 'Last Name', 'trim|required');
            $validation->setRule('phone', 'Phone Number', 'trim|required');
            $validation->setRule('company', 'Company', 'trim');
            $validation->setRule('password', 'Password', 'required|min_length[6]');
            $validation->setRule('pass', 'Confirm Password', 'required|matches[password]');
        }
        if($validation->withRequest($this->request)->run()) {
            $ionAuth = new \App\Libraries\IonAuth();
            $identity = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $email = $this->request->getPost('email');
            $additional_data = [
                'first_name'        => $this->request->getPost('first_name'),
                'last_name'         => $this->request->getPost('last_name'),
                'surname'           => $this->request->getPost('surname'),
                'phone'             => $this->request->getPost('phone'),
                'company'           => $this->request->getPost('company'),
            ];
            $validation->reset();
            $validation->setRule('profile_pic', 'Profile Image', 'uploaded[profile_pic]|is_image[profile_pic]');
            if($validation->withRequest($this->request)->run()) {
                $file = $this->request->getFile('profile_pic');
                $newName = $file->getRandomName();
                if($file->move(FCPATH.'uploads/avatars/', $newName)) {
                    $additional_data['avatar'] = $newName;
//                    if($existing) {
//                        @unlink(FCPATH.(new User())->find($existing)->avatar_file);
//                    }
                }
            }
            $user_model = new User();
            $user_ent = new \App\Entities\User();

            $user_ent->first_name = $additional_data['first_name'];
            $user_ent->last_name = $additional_data['last_name'];
            $user_ent->surname = $additional_data['surname'];
            $user_ent->phone = $additional_data['phone'];
            $user_ent->company = $additional_data['company'];
            $user_ent->username = $identity;
            $user_ent->email = $email;
            $user_ent->avatar = isset($additional_data['avatar']) ? $additional_data['avatar'] : NULL;

            if($existing && is_numeric($existing)) {
                $user_ent->id = $existing;
            } else {
                $user_ent->password = $password;
            }

            //if($userid = $ionAuth->register($identity, $password, $email, $additional_data)) {
            if($user_model->save($user_ent)) {
                if($existing) {
                    do_action('admin_updated', $existing);
                    do_action('user_updated', $existing);
                } else {
                    $userid = $user_model->getInsertID();

                    $ionAuth = new IonAuth();
                    $ionAuth->addToGroup(1, $userid);

                    do_action('admin_registered', $userid);
                    do_action('user_registered', $userid);
                }
                //$this->session->setFlashData('success', 'Admins updated successfully');
                //return $this->response->redirect(site_url(route_to('admin.users.profile', $userid)));
                $return = TRUE;
                $msg = "Admins updated successfully";
            } else {
                //$this->session->setFlashData('error', $ionAuth->errors('list'));
                //return $this->response->redirect(previous_url())->withInput();
                $return = FALSE;
                $msg = $user_model->errors() ? implode(', ', $user_model->errors()) : 'An error occurred';
            }
        } else {
            //$this->session->setFlashData('error', implode('<br/>', $validation->getErrors()));
            //return $this->response->redirect(previous_url())->withInput();
            $msg = implode('<br/>', $validation->getErrors());
            $return = FALSE;
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return ? 'window.location = "'.site_url(route_to('admin.admins.index')).'"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function delete($id)
    {
        $model = new User();
        $user = $model->find($id);
        if($user) {

            if($user->id != 1) {
                $user->active = $user->active == 1 ? 0 : 1;
                try {
                    if ($model->save($user)) {
                        $return = TRUE;
                        $msg = $user->active == 1 ? "Admin activated successfully" : "Admin deactivated successfully";
                    } else {
                        $return = FALSE;
                        $msg = $user->active == 1 ? "Failed to activate admin" : "Failed to deactivate admin";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }
            } else {
                $return = FALSE;
                $msg = "You cannot change the status of this admin.";
            }

//            if($model->delete($id)) {
//                @unlink(FCPATH.$user->avatar_file);
//                $return = TRUE;
//                $msg = "User deleted successfully";
//            } else {
//                $return = FALSE;
//                $msg = "An error occurred";
//            }
        } else {
            $return = FALSE;
            $msg = "User does not exist";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return ? 'window.location = "'.site_url(route_to('admin.admins.index')).'"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }
}
<?php


namespace App\Controllers\OnlineRegistration;


use App\Entities\File;
use App\Entities\Teacher;
use App\Models\Files;
use App\Models\Registrations;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Teachers extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $model = new Registrations();

        $teachers = $model->where('type', 'teacher')->orderBy('id', 'DESC')->where("session",active_session())->findAll();

        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n Teacher Recruitment List \n".getSession()->name;;
        $this->data['teachers'] = $teachers;

        return $this->_renderPage('OnlineRegistration/Teachers/teachers', $this->data);
    }

    public function print()
    {
        $this->data['teachers'] = (new Registrations())->where('type', 'teacher')->where("session",active_session())->findAll();
        return view('OnlineRegistration/Teachers/list/print', $this->data);
    }
    function exportExcel()
    {
        $teachers = (new Registrations())->where('type', 'teacher')->where("session",active_session())->findAll();
        $file_name = 'Teacher Recruitment List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n Teacher Recruitment List \n".getSession()->name;
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
        $sheet->setCellValue('B2', 'D.O.B');
        $sheet->setCellValue('C2', 'Contact');
        $sheet->setCellValue('D2', 'Application Date');
        $sheet->setCellValue('E2', 'Status');

        $count = 3;
        foreach($teachers as $row)
        {
            $status =  isset($teacher->info->status) ? ($teacher->info->status ==0 ? 'Pending' : 'Registered') : 'Pending';
            $sheet->setCellValue('A' . $count, $row->name);
            $sheet->setCellValue('B' . $count, $row->dob);
            $sheet->setCellValue('C' . $count, $row->info->phone_number);
            $sheet->setCellValue('D' . $count, $row->created_at->format('d/m/Y h:i A'));
            $sheet->setCellValue('E' . $count, $status);
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


    public function view($id)
    {
        $model = new Registrations();

        $teacher = $model->where('type', 'teacher')->find($id);

        if(!$teacher) {
            return redirect()->to(previous_url())->with('error', "Registration entry not found");
        }

        $this->data['site_title'] = "Teachers Recruitment";
        $this->data['teacher'] = $teacher;

        return $this->_renderPage('OnlineRegistration/Teachers/viewTeachers', $this->data);
    }

    public function deleteTeacher($id)
    {
        $student = (new Registrations())->find($id);
        if($student) {
            //Delete files first
            @unlink(FCPATH.'uploads/avatars/'.$student->info->profile_pic);

            if((new Registrations())->delete($student->id)) {
                return redirect()->to(site_url(route_to('admin.registration.online.teacher')))->with('success', "Deleted successfully");
            }
        }

        return redirect()->to(site_url(route_to('admin.registration.online.teacher')))->with('error', "Entry not found");
    }

    public function registerTeacher($id)
    {
        $ionAuth = new \App\Libraries\IonAuth();
        $teacher_reg = (new Registrations())->find($id);
        $data =$teacher_reg->info;
        $data->status = 1;
        $to_db = json_encode((array)($data));

        $db = \Config\Database::connect();
        $builder = $db->table('applications');
        $builder->where('id', $id);
        $builder->update(array('info'=>$to_db,'status'=>'registered'));

        //Create teacher user
        $teacher_ = [
            'surname'       => $teacher_reg->first_name,
            'first_name'    => $teacher_reg->middle_name,
            'last_name'     => $teacher_reg->last_name,
            'gender'        => $teacher_reg->gender,
            'phone'         => $teacher_reg->info->phone_number,
        ];

       // return $this->attributes['surname'].' '.$this->attributes['first_name'].' '.$this->attributes['last_name'];
     //   return $this->attributes['first_name'].' '.$this->attributes['middle_name'].' '.$this->attributes['last_name'];

//        $to_db = [
//            'first_name'    => $data['surname'],
//            'middle_name'   =>  $data['first_name'],
//            'last_name'     => $data['last_name'],
//            'gender'        => $data['gender'],
//            'dob'           => $data['dob']
//        ];

        $username = $teacher_reg->info->phone_number;
        $password = random_string('alnum');
        $email = $teacher_reg->info->phone_number.'@'.email_domain();
        if($tId = $ionAuth->register($username, $password, $email, $teacher_, [2])) {
            $user = new \App\Entities\User();
            $user->id = $tId;
            $user->update_usermeta('subcity', $teacher_reg->info->subcity);
            $user->update_usermeta('password', $password);
            $user->update_usermeta('dob', $teacher_reg->dob);
            $user->update_usermeta('phone_mobile', $teacher_reg->info->phone_number);
            $user->update_usermeta('phone_work', $teacher_reg->info->phone_work);
            $user->update_usermeta('woreda', $teacher_reg->info->woreda);
            $user->update_usermeta('house_number', $teacher_reg->info->house_number);
            $user->update_usermeta('previous_school', $teacher_reg->info->previous_school);

            $teacher = new Teacher();
            $teachers_model = new \App\Models\Teachers();
            $teacher->user_id = $user->id;
            $teacher->teacher_number = formatTeacherNumber($tId);
            $teacher->admission_date = date('d/m/Y');
            $teacher->session = active_session();

            if($teachers_model->save($teacher)) {
                $teacher_id = $teachers_model->getInsertID();
                $return = TRUE;
                $msg = "Teacher added successfully";
            } else {
                $teacher_id = FALSE;
                $return = FALSE;
                $msg = "Failed to save teacher";
            }
        } else {
            $return = FALSE;
            $msg = "Failed to create the teacher";
        }

        //Files
        if(isset($teacher_reg->info->teacher_required_files) && !empty($teacher_reg->info->teacher_required_files)) {
            $file_ent = new File();
            $file_model = new Files();
            $file_ent->type = 'teacher';
            $file_ent->uid = $teacher_id;

            $files = json_decode($teacher_reg->info->teacher_required_files);
            foreach ($files as $doc) {
            $file_ent->description = $doc->title;
            $file_ent->file = $doc->file;
            @$file_model->save($file_ent);
            }
        }

        return redirect()->to(site_url(route_to('admin.registration.online.teacher')))->with('success', "Registered successfully");
    }

    public function pdf()
    {
      $model = new Registrations();
      $teachers = $model->where('type', 'teacher')->orderBy('id', 'DESC')->where("session",active_session())->findAll();
      $this->data['teachers'] = $teachers;
      return view('OnlineRegistration/Teachers/list/pdf',$this->data);
    }
}
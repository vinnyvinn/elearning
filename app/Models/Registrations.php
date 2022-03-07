<?php


namespace App\Models;



use CodeIgniter\Config\Config;
use Config\Services;

class Registrations extends \CodeIgniter\Model
{
    protected $table = 'applications';
    protected $primaryKey = 'id';

    protected $returnType = '\App\Entities\Registration';
    protected $allowedFields = ['first_name', 'middle_name', 'last_name', 'gender', 'gender', 'dob', 'type', 'info', 'status','session', 'existing'];

    private $baseRules = [
        'surname'    => 'required',
        'first_name'   => 'required',
        'last_name'     => 'required',
        'gender'        => 'required|in_list[Male,Female]',
        'dob'           => 'required|valid_date[d/m/Y]',
        'profile_pic'   => 'uploaded[profile_pic]|is_image[profile_pic]|permit_empty'
    ];
    /**
     * @var mixed|string|null
     */
    public $error;

    public function saveRecord()
    {
        $request = \Config\Services::request();
        $data = $request->getPost();

        $validation = Services::validation();
        $form = $data['form'];
        $existing_student = FALSE;
        if($form == 'student') {
            $rules = [
                'class'     => 'required',
             //   'section'   => 'required_with[existing_student]',
                'admission_number'    => 'required_with[existing_student]',
                'admission_date'    => 'required_with[existing_student]',
                //'language'   => 'required',
                'previous_school'   => 'required_without[existing_student]',
                'parent_surname'    => 'required',
                'parent_first_name'     => 'required',
                'parent_last_name'      => 'required',
                'parent_phone_mobile'   => 'required',
                'parent_phone_work'     => 'required',
                'subcity'           => 'required',
                'woreda'            => 'required',
                'house_number'      => 'required',
                'parent_profile_pic'    => 'uploaded[parent_profile_pic]|is_image[parent_profile_pic]|permit_empty',
                'tac'               => 'required|is_numeric'
            ];
            if(isset($data['existing_student']) && $data['existing_student'] == 1) {
                $existing_student = TRUE;
            }
        } else if ($form == 'teacher') {
            $rules = [
                'subcity'           => 'required',
                'woreda'            => 'required',
                'house_number'      => 'required',
                'phone_mobile'      => 'required',
                'subject_to_teach'  => 'required|is_numeric',
                'education_level'   => 'required',
                'experience'        => 'required|is_numeric|is_natural'
            ];
        }
        else if ($form == 'administration') {
            $rules = [
                'subcity'           => 'required',
                'woreda'            => 'required',
                'house_number'      => 'required',
                'phone_mobile'      => 'required',
                'position_employed'  => 'required',
                'education_level'   => 'required',
                'experience'        => 'required|is_numeric|is_natural'
            ];
        }
        $rules = array_merge($this->baseRules, $rules);

        $validation->setRules($rules);
        if($validation->run($data)) {
            $to_db = [
                'first_name'    => $data['surname'],
                'middle_name'   =>  $data['first_name'],
                'last_name'     => $data['last_name'],
                'gender'        => $data['gender'],
                'dob'           => $data['dob'],
                'session'       => active_session()
            ];
            $file = $request->getFile('profile_pic');
            $avatar = FALSE;
            if(isset($file) && $file && $file->isValid()) {
                $newName = $file->getRandomName();
                if ($file->move(FCPATH . 'uploads/avatars/', $newName)) {
                    $avatar = $newName;
                }
            }

            $file = $request->getFile('parent_profile_pic');
            $parent_avatar = FALSE;
            if(isset($file) && $file && $file->isValid()) {
                $newName = $file->getRandomName();
                if ($file->move(FCPATH . 'uploads/avatars/', $newName)) {
                    $parent_avatar = $newName;
                }
            }
            $slip = $request->getFile('slip');
            $slip_file = FALSE;
            if(isset($slip) && $slip && $slip->isValid()) {
                $newName = $slip->getRandomName();
                if ($slip->move(FCPATH . 'uploads/deposit-slips/', $newName)) {
                    $slip_file = $newName;
                }
            }
            $docs = $request->getFiles();
            $teachers_files = array();
            if (!empty($docs['teacher_required_file']) && !empty($_POST['teacher_required_file_title'])) {
                foreach ($docs['teacher_required_file'] as $k_p => $pic) {
                    foreach ($_POST['teacher_required_file_title'] as $k_ => $t) {
                        if ($k_p == $k_) {
                            if ($pic->isValid() && !$pic->hasMoved()) {
                                $newName = $pic->getRandomName();
                                if ($pic->move(FCPATH . 'uploads/files/', $newName)) {
                                    array_push($teachers_files, array('title'=>$t,'file'=>$newName));
                                }
                            }
                        }
                    }
                }
            }

            $admin_files = array();
            if (!empty($docs['admin_required_file']) && !empty($_POST['admin_required_file_title'])) {
                foreach ($docs['admin_required_file'] as $k_p => $pic) {
                    foreach ($_POST['admin_required_file_title'] as $k_ => $t) {
                        if ($k_p == $k_) {
                            if ($pic->isValid() && !$pic->hasMoved()) {
                                $newName = $pic->getRandomName();
                                if ($pic->move(FCPATH . 'uploads/files/', $newName)) {
                                    array_push($admin_files, array('title'=>$t,'file'=>$newName));
                                }
                            }
                        }
                    }
                }

            }

            if($form == 'student') {
                $to_db['existing'] = $existing_student ? 1 : 0;
                $extra = [
                    'profile_pic'   => $avatar,
                    'class'         => $data['class'],
                    'section'       => isset($data['section']) ? $data['section'] :'',
                    'admission_date'    => $data['admission_date'],
                    'admission_number'  => isset($data['admission_number']) ? $data['admission_number'] : '',
                    'language_spoken'      => @$data['language'],
                    'previous_school'   => isset($data['previous_school']) ? $data['previous_school'] : '',
                    'parent'    => [
                        'surname'   => $data['parent_surname'],
                        'first_name'    => $data['parent_first_name'],
                        'last_name'     => $data['parent_last_name'],
                        'mobile_number' => $data['parent_phone_mobile'],
                        'work_phone'    => $data['parent_phone_work'],
                        'subcity'       => $data['subcity'],
                        'woreda'        => $data['woreda'],
                        'house_number'          => $data['house_number'],
                        'avatar'        => $parent_avatar
                    ],
                    'deposit_slip'  => $slip_file
                ];
            } else if ($form == 'teacher') {
                $extra = [
                    'profile_pic'   => $avatar,
                    'phone_number'  => $data['phone_mobile'],
                    'phone_work'    => $data['phone_work'],
                    'subcity'       => $data['subcity'],
                    'woreda'    => $data['woreda'],
                    'house_number'  => $data['house_number'],
                    'previous_school'   => $data['previous_school'],
                    'subject'       => $data['subject_to_teach'],
                    'education_level'   => $data['education_level'],
                    'experience'   => $data['experience'],
                    'status'   => 0,
                    'teacher_required_files' => $teachers_files ? json_encode($teachers_files) : ''
                ];
                $to_db['type'] = 'teacher';
            }
            else if ($form == 'administration') {
                $extra = [
                    'profile_pic'   => $avatar,
                    'phone_number'  => $data['phone_mobile'],
                    'phone_work'    => $data['phone_work'],
                    'subcity'       => $data['subcity'],
                    'woreda'    => $data['woreda'],
                    'house_number'  => $data['house_number'],
                    'previous_school'   => $data['previous_school'],
                    'position_employed'       => $data['position_employed'],
                    'education_level'   => $data['education_level'],
                    'experience'   => $data['experience'],
                    'admin_required_files' => $admin_files ? json_encode($admin_files) : ''
                ];
                $to_db['type'] = 'administration';
            }

            $to_db['info'] = json_encode($extra);

            try {
                if ($this->save($to_db)) {
                    return TRUE;
                } else {
                    $this->error = "A database error occured";

                    return FALSE;
                }
            } catch (\ReflectionException $e) {
                $this->error = $e->getMessage();
                return FALSE;
            }
        } else {
            $this->error = implode('<br/>', $validation->getErrors());
            return FALSE;
        }
    }
}
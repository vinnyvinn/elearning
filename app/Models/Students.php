<?php


namespace App\Models;


use App\Entities\Contact;
use App\Entities\File;
use App\Entities\Student;
use App\Libraries\IonAuth;
use CodeIgniter\Model;
use Config\Services;
use Config\Validation;

class Students extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';

    public $returnType = '\App\Entities\Student';

    public $allowedFields = ['admission_number', 'session', 'user_id', 'class', 'section', 'parent', 'contact', 'active', 'admission_date','session'];

    protected $validationRules = [
        'dob' => ['label' => 'Date of Birth', 'rules' => 'permit_empty'],
        'class' => ['label' => 'Grade', 'rules' => 'required|is_natural_no_zero'],
        'section' => ['label' => 'Section', 'rules' => 'required|is_natural_no_zero'],
        'admission_date' => ['label' => 'Admission Date', 'rules' => 'required']
    ];

    protected $parentsValidation = [
        'surname' => ['label' => 'Surname', 'rules' => 'required'],
        'first_name' => ['label' => 'First Name', 'rules' => 'required'],
        'last_name' => ['label' => 'Last Name', 'rules' => 'required'],
        'parent_phone_mobile' => ['label' => 'Mobile Phone Number', 'rules' => 'required'],
        'subcity' => ['label' => 'Subcity', 'rules' => 'required'],
        'woreda' => ['label' => 'Woreda', 'rules' => 'required'],
        'house_number' => ['label' => 'House Number', 'rules' => 'required'],
    ];
    /**
     * @var bool|mixed|null
     */
    public $parentContact;
    /**
     * @var bool|mixed|null
     */
    public $parentUsername;
    /**
     * @var bool|mixed|string|null
     */
    public $parentPassword;
    /**
     * @var mixed|string|null
     */
    public $studentUsername;
    /**
     * @var mixed|string|null
     */
    public $studentPassword;

    public function createStudent()
    {
        $ionAuth = new IonAuth();
        $validation = Services::validation(new Validation(), false);
        $request = Services::request();
        // Create student user
        $users_model = new User();
        $user_entity = new \App\Entities\User();

        if(!$validation->setRules($this->validationRules)->withRequest($request)->run()) {
            return $validation->getErrors();
        }
        $validation->reset();
        if(!$validation->setRules($this->parentsValidation)->withRequest($request)->run()) {
            return $validation->getErrors();
        }
        $validation->reset();
        $parent_phone = $request->getPost('parent_phone_mobile');

        $username = $parent_phone . 's';
        $student_username = $username;
        $user_entity->surname = $request->getPost('surname');
        $user_entity->first_name = $request->getPost('first_name');
        $user_entity->last_name = $request->getPost('last_name');
        $user_entity->gender = $request->getPost('gender');
        $user_entity->username = $username;
        $user_entity->email = $username . '@' . email_domain();
        $student_password = random_string('alnum');
        $user_entity->password = $student_password;

        $validation->reset();
        $validation->setRule('profile_pic', 'Profile Image', 'uploaded[profile_pic]|is_image[profile_pic]');
        if ($validation->withRequest($request)->run()) {
            $file = $request->getFile('profile_pic');
            $newName = $file->getRandomName();
            if ($file->move(FCPATH . 'uploads/avatars/', $newName)) {
                $user_entity->avatar = $newName;
            }
        }
        //TODO: Fix this hack where a second student cannot be registered by parents phone number bcoz another exists
        $xCount = $users_model->where('username', $username)->countAll();
        if ($xCount > 0) {
            //$username = $username . 's';
            $username = str_pad($username, $xCount+1, STR_PAD_RIGHT);
            $user_entity->username = $username;
            $user_entity->email = $username . '@' . email_domain();
        }

        if ($users_model->save($user_entity)) { // 3 is the students group
            $student_id = $users_model->getInsertID();
            do_action('student_user_registered', $student_id);
            //Add student to student group
            $ionAuth->addToGroup(3, $student_id); // 3 is the students group

            $student = new \App\Entities\User();
            $student->id = $student_id;
            $student->update_usermeta('password', $student_password);
            //$student->update_usermeta('language_spoken', $request->getPost('language'));
            $student->update_usermeta('dob', $request->getPost('dob'));
            $student->update_usermeta('previous_school', $request->getPost('previous_school'));
            $student->update_usermeta('known_disabilities', $request->getPost('child_disabilities'));
            $student->update_usermeta('known_diseases', $request->getPost('child_diseases'));
            $student->update_usermeta('special_talents', $request->getPost('special_talents'));
            $student->update_usermeta('future_ambitions', $request->getPost('future_ambitions'));
            $student->update_usermeta('amharic', $request->getPost('amharic'));
            $student->update_usermeta('math', $request->getPost('math'));
            $student->update_usermeta('english', $request->getPost('english'));
            $student->update_usermeta('social_science', $request->getPost('social_science'));
            $student->update_usermeta('general_science', $request->getPost('general_science'));
            $student->update_usermeta('physics', $request->getPost('physics'));
            $student->update_usermeta('biology', $request->getPost('biology'));
            $student->update_usermeta('chemistry', $request->getPost('chemistry'));
            $student->update_usermeta('eng_speaking', $request->getPost('eng_speaking'));
            $student->update_usermeta('eng_listening', $request->getPost('eng_listening'));
            $student->update_usermeta('eng_reading', $request->getPost('eng_reading'));
            $student->update_usermeta('eng_writing', $request->getPost('eng_writing'));
            $student->update_usermeta('nationality', $request->getPost('nationality'));
            $tid = $request->getPost('transport_route');
            if ($tid && is_numeric($tid)) {
                $student->update_usermeta('transportation_route', $tid);
            }

            // TODO: Check if parent/contact is already registered before adding a new one
            // TODO: Probably use the id of the parent/contact already registered
            // Create parent/guardian user
            $username = $parent_phone;
            $email = $parent_phone . '@' . email_domain();
            $parent_password = random_string('alnum');
            $password = $parent_password;

            $user_entity->username = $username;
            $user_entity->email = $email;
            $user_entity->password = $password;
            $user_entity->surname = $request->getPost('parent_surname');
            $user_entity->first_name = $request->getPost('parent_first_name');
            $user_entity->last_name = $request->getPost('parent_last_name');
            $user_entity->phone = $parent_phone;

            $validation->reset();
            $validation->setRule('parent_profile_pic', 'Profile Image', 'uploaded[parent_profile_pic]|is_image[parent_profile_pic]');
            if ($validation->withRequest($request)->run()) {
                $file = $request->getFile('parent_profile_pic');
                $newName = $file->getRandomName();
                if ($file->move(FCPATH . 'uploads/avatars/', $newName)) {
                    $user_entity->avatar = $newName;
                }
            }

            //$parent_id = $ionAuth->register($username, $password, $email, $parent, [4]); // 4 is the parents group
            //check if parent exists
            $xParentExists = $users_model->where('username', $parent_phone)->get()->getFirstRow();
            if ($xParentExists) {
                $parent_id = $xParentExists->id;
            } elseif ($users_model->save($user_entity)) {
                $parent_id = $users_model->getInsertID();
                do_action('parent_user_registered', $parent_id);
                //Add parent to student group
                $ionAuth->addToGroup(4, $parent_id); // 4 is the parents group
                $parent = new \App\Entities\User();
                $parent->id = $parent_id;
                $parent->update_usermeta('password', $parent_password);
                $parent->update_usermeta('mobile_phone_number', $request->getPost('parent_phone_mobile'));
                $parent->update_usermeta('mobile_phone_work', $request->getPost('parent_phone_work'));
                $parent->update_usermeta('subcity', $request->getPost('subcity'));
                $parent->update_usermeta('woreda', $request->getPost('woreda'));
                $parent->update_usermeta('house_number', $request->getPost('house_number'));
            } else {
                $parent_id = FALSE;
            }
             //Create Emergency Contact user
//            $contact_model = new Contacts();
//            $contact_entity = new Contact();
//
//            $contact_entity->surname = $request->getPost('e_surname');
//            $contact_entity->first_name = $request->getPost('e_first_name');
//            $contact_entity->last_name = $request->getPost('e_last_name');
//            $contact_entity->phone_mobile = $request->getPost('e_phone_mobile');
//            $contact_entity->phone_work = $request->getPost('e_phone_work');
//            $contact_entity->subcity = $request->getPost('e_subcity');
//            $contact_entity->woreda = $request->getPost('e_woreda');
//            $contact_entity->house_number = $request->getPost('e_house_number');

//            $xContactExists = $contact_model->where('phone_mobile', $contact_entity->phone_mobile)->get()->getFirstRow();
//            if($xContactExists) {
//                $contact_id =$xContactExists->id;
//            } else
//            if ($contact_model->save($contact_entity)) {
//                $contact_id = $contact_model->getInsertID();
//            } else {
//                $contact_id = FALSE;
//            }
            $contact_id = FALSE;

            //Create student entry
            $ent = new Student();
            $ent->session = active_session();
            $ent->user_id = $student_id;
            $ent->parent = $parent_id ? $parent_id : NULL;
            $ent->contact = $contact_id ? $contact_id : NULL;
            $ent->admission_date = $request->getPost('admission_date');
            $ent->class = $request->getPost('class');
            $ent->section = $request->getPost('section');
            $update = FALSE;
            $xAdm = $request->getPost('admission_number');
            $xAdm = trim($xAdm);
            if ($xAdm && $xAdm != '') {
                $ent->admission_number = $xAdm;
            } else {
                $ent->admission_number = time();
                $update = TRUE;
            }
            //$model = new Students();
            try {
                if ($this->save($ent)) {
                    $id = $this->getInsertID();
                    if ($update) {
                        $ent->admission_number = formatAdmissionNumber($id);
                        $ent->id = $id;
                        @$this->save($ent);
                    }
                    $update_student = [
                        'id' => $student_id,
                        'username' => $ent->admission_number
                    ];

                    $users_model->save($update_student);

                    if ($id && $docs = $request->getFiles()) {
                        $labels = $request->getPost('title');
                        $file_ent = new File();
                        $file_model = new Files();
                        $file_ent->type = 'student';
                        $file_ent->uid = $id;
                        $i = 0;
                        foreach ($docs['doc'] as $doc) {
                            if ($doc->isValid() && !$doc->hasMoved()) {
                                $newName = $doc->getRandomName();
                                if ($doc->move(FCPATH . 'uploads/files/', $newName)) {
                                    $file_ent->description = $labels[$i];
                                    $file_ent->file = $newName;

                                    @$file_model->save($file_ent);
                                }
                            }
                            $i++;
                        }
                    }

                    $this->parentContact = isset($parent) ? $parent->usermeta('mobile_phone_number', FALSE) : FALSE;
                    $this->parentUsername = isset($parent) ? $parent_phone : FALSE;
                    $this->parentPassword = isset($parent) ? $parent_password : FALSE;
                    $this->studentUsername = $student_username;
                    $this->studentPassword = $student_password;
                    return TRUE;
                } else {
                    return $this->errors();
                }
            } catch (\ReflectionException $e) {
                return [
                    $e->getMessage()
                ];
            }
        } else {
            return $users_model->errors();
        }
    }
    public function createStudentNew($student_id_,$session,$section,$class)
    {
        $student_= (new Students())->find($student_id_);

            //Create student entry
            $ent = new Student();
            $ent->session = $session;
            $ent->user_id = $student_->user_id;
            $ent->parent = $student_->parentid;
            $ent->contact =$student_->contactid;
            $ent->admission_number = $student_->admission_number;
            $ent->admission_date = $student_->admission_date;
            $ent->class = $class;
            $ent->section = $section;


            try {
                if ($this->save($ent)) {
                    $id = $this->getInsertID();
                    if ($docs = (new Files())->where('uid',$student_id_)->where('type','student')->get()->getResult()) {
                        $file_ent = new File();
                        $file_model = new Files();
                        $file_ent->type = 'student';
                        $file_ent->uid = $id;
                        foreach ($docs as $doc) {
                        $file_ent->description = $doc->description;
                        $file_ent->file = $doc->file;
                        @$file_model->save($file_ent);
                        }
                    }
                    return TRUE;
                } else {
                    return $this->errors();
                }
            } catch (\ReflectionException $e) {
                return [
                    $e->getMessage()
                ];
            }
        }
    public function registerStudents($id,$class,$section)
    {
        $student_ = (new Registrations())->find($id);
        $ionAuth = new IonAuth();
        $users_model = new User();
        $user_entity = new \App\Entities\User();
        $students = new \App\Models\Students();

        $parent_phone = $student_->info->parent->mobile_number;
        $username = $parent_phone . 's';
        $student_username = $username;
        $user_entity->surname = $student_->first_name;
        $user_entity->first_name = $student_->middle_name;
        $user_entity->last_name = $student_->last_name;
        $user_entity->gender = $student_->gender;
        $user_entity->username = $username;
        $user_entity->email = $username . '@' . email_domain();
        $student_password = random_string('alnum');
        $user_entity->password = $student_password;

        //TODO: Fix this hack where a second student cannot be registered by parents phone number bcoz another exists
        $xCount = $users_model->where('username', $username)->countAll();
        if ($xCount > 0) {
            //$username = $username . 's';
            $username = str_pad($username, $xCount+1, STR_PAD_RIGHT);
            $user_entity->username = $username;
            $user_entity->email = $username . '@' . email_domain();
        }


        if ($users_model->save($user_entity)) { // 3 is the students group
            $student_id = $users_model->getInsertID();
            do_action('student_user_registered', $student_id);
            //Add student to student group
            $ionAuth->addToGroup(3, $student_id); // 3 is the students group

            $student = new \App\Entities\User();
            $student->id = $student_id;
            $student->update_usermeta('password', $student_password);
            //$student->update_usermeta('language_spoken', $request->getPost('language'));
            $student->update_usermeta('dob', $student_->dob);
            $student->update_usermeta('previous_school', $student_->info->previous_school);

            // TODO: Check if parent/contact is already registered before adding a new one
            // TODO: Probably use the id of the parent/contact already registered
            // Create parent/guardian user
            $username = $parent_phone;
            $email = $parent_phone . '@' . email_domain();
            $parent_password = random_string('alnum');
            $password = $parent_password;

            $user_entity->username = $username;
            $user_entity->email = $email;
            $user_entity->password = $password;
            $user_entity->surname =  $student_->info->parent->surname;
            $user_entity->first_name = $student_->info->parent->first_name;
            $user_entity->last_name = $student_->info->parent->last_name;
            $user_entity->phone = $parent_phone;
            $user_entity->avatar = $student_->info->parent->avatar?:'';

            //$parent_id = $ionAuth->register($username, $password, $email, $parent, [4]); // 4 is the parents group
            //check if parent exists
            $xParentExists = $users_model->where('username', $parent_phone)->get()->getFirstRow();
            if ($xParentExists) {
                $parent_id = $xParentExists->id;
            } elseif ($users_model->save($user_entity)) {
                $parent_id = $users_model->getInsertID();
                do_action('parent_user_registered', $parent_id);
                //Add parent to student group
                $ionAuth->addToGroup(4, $parent_id); // 4 is the parents group
                $parent = new \App\Entities\User();
                $parent->id = $parent_id;
                $parent->update_usermeta('password', $parent_password);
                $parent->update_usermeta('mobile_phone_number', $student_->info->parent->mobile_number);
                $parent->update_usermeta('mobile_phone_work', $student_->info->parent->work_phone);
                $parent->update_usermeta('subcity', $student_->info->parent->subcity);
                $parent->update_usermeta('woreda', $student_->info->parent->woreda);
                $parent->update_usermeta('house_number', $student_->info->parent->house_number);
            } else {
                $parent_id = FALSE;
            }

            $contact_id = FALSE;

            //Create student entry
            $ent = new Student();
            $ent->session = active_session();
            $ent->user_id = $student_id;
            $ent->parent = $parent_id ? $parent_id : NULL;
            $ent->contact = $contact_id ? $contact_id : NULL;
            $ent->admission_date = $student_->info->admission_date;
            $ent->class = $class;
            $ent->section = $section;
            $update = FALSE;
            $xAdm = $student_->info->admission_number;
            $xAdm = trim($xAdm);
            if ($xAdm && $xAdm != '') {
                $ent->admission_number = $xAdm;
            } else {
                $ent->admission_number = time();
                $update = TRUE;
            }
            //$model = new Students();

            try {
                if ($students->save($ent)) {
                    $id = $students->getInsertID();
                    if ($update) {
                        $ent->admission_number = formatAdmissionNumber($id);
                        $ent->id = $id;
                        $students->save($ent);
                    }
                    $update_student = [
                        'id' => $student_id,
                        'username' => $ent->admission_number
                    ];

                    $users_model->save($update_student);

                    $students->parentContact = isset($parent) ? $parent->usermeta('mobile_phone_number', FALSE) : FALSE;
                    $students->parentUsername = isset($parent) ? $parent_phone : FALSE;
                    $students->parentPassword = isset($parent) ? $parent_password : FALSE;
                    $students->studentUsername = $student_username;
                    $students->studentPassword = $student_password;
                    return TRUE;
                } else {
                    return $students->errors();
                }
                return TRUE;
            } catch (\ReflectionException $e) {
                return [
                    $e->getMessage()
                ];
            }
        } else {
            return $users_model->errors();
        }
        return TRUE;
    }
    public function updateStudent($studentId)
    {
        $xStudentEntity = $this->find($studentId);
        if (!$xStudentEntity) return FALSE;

        $ionAuth = new IonAuth();
        $validation = Services::validation();
        $request = Services::request();
        // Create student user
        $users_model = new User();
        $user_entity = new \App\Entities\User();

        if(!$validation->setRules($this->validationRules)->withRequest($request)->run()) {
            return $validation->getErrors();
        }
        $validation->reset();
        if(!$validation->setRules($this->parentsValidation)->withRequest($request)->run()) {
            return $validation->getErrors();
        }
        $validation->reset();

        $parent_phone = $request->getPost('parent_phone_mobile');

        $username = $parent_phone . 's';
        $admission_number = trim($request->getPost('admission_number'));
        $user_entity->surname = $request->getPost('surname');
        $user_entity->first_name = $request->getPost('first_name');
        $user_entity->last_name = $request->getPost('last_name');
        $user_entity->gender = $request->getPost('gender');
        $user_entity->username = $admission_number;
        $user_entity->email = $username . '@' . email_domain();
        $student_password = random_string('alnum');
        //$user_entity->password = $student_password;
        $user_entity->id = $xStudentEntity->user_id;

        $validation->reset();
        $validation->setRule('profile_pic', 'Profile Image', 'uploaded[profile_pic]|is_image[profile_pic]');
        if ($validation->withRequest($request)->run()) {
            $file = $request->getFile('profile_pic');
            $newName = $file->getRandomName();
            if ($file->move(FCPATH . 'uploads/avatars/', $newName)) {
                $user_entity->avatar = $newName;
            }
        }
        if ($users_model->save($user_entity)) { // 3 is the students group
            $student_id = $xStudentEntity->user_id;
            do_action('student_user_updated', $student_id);
            //Add student to student group
            //$ionAuth->addToGroup(3, $student_id); // 3 is the students group

            $student = new \App\Entities\User();
            $student->id = $student_id;
            //$student->update_usermeta('password', $student_password);
            //$student->update_usermeta('language_spoken', $request->getPost('language'));
            $student->update_usermeta('dob', $request->getPost('dob'));
            $student->update_usermeta('previous_school', $request->getPost('previous_school'));
            $student->update_usermeta('known_disabilities', $request->getPost('child_disabilities'));
            $student->update_usermeta('known_diseases', $request->getPost('child_diseases'));
            $student->update_usermeta('special_talents', $request->getPost('special_talents'));
            $student->update_usermeta('future_ambitions', $request->getPost('future_ambitions'));
            $student->update_usermeta('amharic', $request->getPost('amharic'));
            $student->update_usermeta('math', $request->getPost('math'));
            $student->update_usermeta('english', $request->getPost('english'));
            $student->update_usermeta('social_science', $request->getPost('social_science'));
            $student->update_usermeta('general_science', $request->getPost('general_science'));
            $student->update_usermeta('physics', $request->getPost('physics'));
            $student->update_usermeta('biology', $request->getPost('biology'));
            $student->update_usermeta('chemistry', $request->getPost('chemistry'));
            $student->update_usermeta('eng_speaking', $request->getPost('eng_speaking'));
            $student->update_usermeta('eng_listening', $request->getPost('eng_listening'));
            $student->update_usermeta('eng_reading', $request->getPost('eng_reading'));
            $student->update_usermeta('eng_writing', $request->getPost('eng_writing'));
            $tid = $request->getPost('transport_route');
            if ($tid && is_numeric($tid)) {
                $student->update_usermeta('transportation_route', $tid);
            }

            // TODO: Check if parent/contact is already registered before adding a new one
            // TODO: Probably use the id of the parent/contact already registered
            // Create parent/guardian user
            $username = $parent_phone;
            $email = $parent_phone . '@' . email_domain();
            $parent_password = random_string('alnum');
            $password = $parent_password;

            $user_entity->username = $username;
            $user_entity->email = $email;
            //$user_entity->password = $password;
            $user_entity->surname = $request->getPost('parent_surname');
            $user_entity->first_name = $request->getPost('parent_first_name');
            $user_entity->last_name = $request->getPost('parent_last_name');
            $user_entity->phone = $parent_phone;
            $user_entity->id = $xStudentEntity->parent->id;

            $validation->reset();
            $validation->setRule('parent_profile_pic', 'Profile Image', 'uploaded[parent_profile_pic]|is_image[parent_profile_pic]');
            if ($validation->withRequest($request)->run()) {
                $file = $request->getFile('parent_profile_pic');

                $parentName = $file->getRandomName();
                if ($file->move(FCPATH . 'uploads/avatars/', $parentName)) {
                    $user_entity->avatar = $parentName;
                }
            }

            //$parent_id = $ionAuth->register($username, $password, $email, $parent, [4]); // 4 is the parents group
            //check if parent exists
            if ($users_model->save($user_entity)) {
                $parent_id = $xStudentEntity->parent->id;
                do_action('parent_user_updated', $parent_id);
                //Add parent to student group
                //$ionAuth->addToGroup(4, $parent_id); // 4 is the parents group
                $parent = new \App\Entities\User();
                $parent->id = $parent_id;
                //$parent->update_usermeta('password', $parent_password);
                $parent->update_usermeta('mobile_phone_number', $request->getPost('parent_phone_mobile'));
                $parent->update_usermeta('mobile_phone_work', $request->getPost('parent_phone_work'));
                $parent->update_usermeta('subcity', $request->getPost('subcity'));
                $parent->update_usermeta('woreda', $request->getPost('woreda'));
                if (isset($parentName))
                $parent->update_usermeta("parent_avatar",$parentName);
                $parent->update_usermeta('house_number', $request->getPost('house_number'));
            } else {
                $parent_id = FALSE;
            }

            // Create Emergency Contact user
            $contact_model = new Contacts();
            $contact_entity = new Contact();

//            $contact_entity->surname = $request->getPost('e_surname');
//            $contact_entity->first_name = $request->getPost('e_first_name');
//            $contact_entity->last_name = $request->getPost('e_last_name');
//            $contact_entity->phone_mobile = $request->getPost('e_phone_mobile');
//            $contact_entity->phone_work = $request->getPost('e_phone_work');
//            $contact_entity->subcity = $request->getPost('e_subcity');
//            $contact_entity->woreda = $request->getPost('e_woreda');
//            $contact_entity->house_number = $request->getPost('e_house_number');
//            $contact_entity->id = $xStudentEntity->contact->id;

//            if ($contact_model->save($contact_entity)) {
//                $contact_id = $xStudentEntity->contact->id;
//            } else {
//                $contact_id = FALSE;
//            }
            $contact_id = FALSE;

            // Create student entry
            $ent = new Student();
            //$ent->session = active_session();
            //$ent->user_id = $student_id;
            if ($parent_id) $ent->parent = $parent_id;
            if ($contact_id) $ent->contact = $contact_id;
            $ent->admission_date = $request->getPost('admission_date');
            $ent->class = $request->getPost('class');
            $ent->section = $request->getPost('section');
            $ent->admission_number = $admission_number;
            $ent->id = $xStudentEntity->id;

            if ($xStudentEntity->id && $docs = $request->getFiles()) {
                $labels = $request->getPost('title');
                $file_ent = new File();
                $file_model = new Files();
                $file_ent->type = 'student';
                $file_ent->uid = $xStudentEntity->id;
                $i = 0;
                foreach ($docs['doc'] as $doc) {
                    if ($doc->isValid() && !$doc->hasMoved()) {
                        $newName = $doc->getRandomName();
                        if ($doc->move(FCPATH . 'uploads/files/', $newName)) {
                            $file_ent->description = $labels[$i];
                            $file_ent->file = $newName;

                            @$file_model->save($file_ent);
                        }
                    }
                    $i++;
                }
            }

            //$model = new Students();
            if ($ent->hasChanged()) {
                if ($this->save($ent)) {
                    return TRUE;
                } else {
                    return $this->errors();
                }
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }
}
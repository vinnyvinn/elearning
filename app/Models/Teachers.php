<?php


namespace App\Models;


use App\Entities\Contact;
use App\Entities\File;
use App\Entities\Teacher;
use CodeIgniter\Model;

class Teachers extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'id';

    protected $returnType = 'App\Entities\Teacher';

    protected $allowedFields = ['user_id', 'teacher_number', 'admission_date', 'contact', 'active','signature','is_director','director_classes','session'];

    public function createTeacher()
    {
        $ionAuth = new \App\Libraries\IonAuth();
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();
        //Create teacher user
        $teacher = [
            'surname'       => $request->getPost('surname'),
            'first_name'    => $request->getPost('first_name'),
            'last_name'     => $request->getPost('last_name'),
            'gender'        => $request->getPost('gender'),
            'phone'         => $request->getPost('phone_mobile'),
        ];

//        $to_db = [
//            'first_name'    => $data['surname'],
//            'middle_name'   =>  $data['first_name'],
//            'last_name'     => $data['last_name'],
//            'gender'        => $data['gender'],
//            'dob'           => $data['dob']
//        ];

        $username = $request->getPost('phone_mobile');
        $password = random_string('alnum');
        $email = $request->getPost('phone_mobile').'@'.email_domain();

        $validation->reset();
        $validation->setRule('profile_pic', 'Profile Image', 'uploaded[profile_pic]|is_image[profile_pic]');
        $validation->setRule('signature', 'Signature Image', 'uploaded[signature]|is_image[signature]');
        if($validation->withRequest($request)->run()) {
            $file = $request->getFile('profile_pic');
            $newName = $file->getRandomName();
            if($file->move(FCPATH.'uploads/avatars/', $newName)) {
                $teacher['avatar'] = $newName;
            }
        }

        $file2 = $request->getFile('signature');
        $newName2 = $file2->getRandomName();
        if($file2->move(FCPATH.'uploads/', $newName2)) {
            $teacher['signature'] = $newName2;
        }

//        $folderPath = "uploads/";
//        $image_parts = explode(";base64,", $_POST['signed']);
//        $image_type_aux = explode("image/", $image_parts[0]);
//        $image_type = $image_type_aux[1];
//        $image_base64 = base64_decode($image_parts[1]);
//        $file = $folderPath . uniqid() . '.'.$image_type;
//        file_put_contents($file, $image_base64);


        if($tId = $ionAuth->register($username, $password, $email, $teacher, [2])) {
            $user = new \App\Entities\User();
            $user->id = $tId;
            $user->update_usermeta('subcity', $request->getPost('subcity'));
            $user->update_usermeta('password', $password);
            $user->update_usermeta('dob', $request->getPost('dob'));
            $user->update_usermeta('phone_mobile', $request->getPost('phone_mobile'));
            $user->update_usermeta('phone_work', $request->getPost('phone_work'));
            $user->update_usermeta('woreda', $request->getPost('woreda'));
            $user->update_usermeta('house_number', $request->getPost('house_number'));
            $user->update_usermeta('previous_school', $request->getPost('previous_school'));
            $user->update_usermeta('known_diseases', $request->getPost('known_diseases'));
            $user->update_usermeta('known_disabilities', $request->getPost('known_disabilities'));

            $teacher = new Teacher();
            $teachers_model = new Teachers();
            $teacher->user_id = $user->id;
            $teacher->signature = $file2;
            $teacher->teacher_number = formatTeacherNumber($tId);
            $teacher->admission_date = $request->getPost('admission_date');

            if (isset($_POST['is_director']) && $_POST['is_director'] ==1){
                $teacher->is_director = 1;
                $teacher->director_classes = json_encode($_POST['director_classes']);
            }
             //Contact
//            $contact = new Contact();
//            $contact->surname = $request->getPost('e_surname');
//            $contact->first_name = $request->getPost('e_first_name');
//            $contact->last_name = $request->getPost('e_last_name');
//            $contact->phone_mobile = $request->getPost('e_phone_mobile');
//            $contact->phone_work = $request->getPost('e_phone_work');
//            $contact->subcity = $request->getPost('e_subcity');
//            $contact->woreda = $request->getPost('e_woreda');
//            $contact->house_number = $request->getPost('e_house_number');
//            $contact_model = new Contacts();
//            if($contact_model->save($contact)) {
//                $teacher->contact = $contact_model->getInsertID();
//            }
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

        if($teacher_id && $docs = $request->getFiles()) {
            $labels = $request->getPost('title');
            $file_ent = new File();
            $file_model = new Files();
            $file_ent->type = 'teacher';
            $file_ent->uid = $teacher_id;
            $i = 0;
            foreach ($docs['doc'] as $doc) {
                if ($doc->isValid() && ! $doc->hasMoved())
                {
                    $newName = $doc->getRandomName();
                    if($doc->move(FCPATH.'uploads/files/', $newName)){
                        $file_ent->description = $labels[$i];
                        $file_ent->file = $newName;

                        @$file_model->save($file_ent);
                    }
                }
                $i++;
            }
        }

        if($return) {
            return true;
        }

        return $msg;
    }
    public function createTeacherNew($teacher_id_,$session)
    {

        $teacher_ = (new Teachers())->find($teacher_id_);
        $user_ = (new User())->find($teacher_->user_id);

             //Create teacher
             $teacher_id = '';
                $to_db = array('user_id'=>$user_->id,'signature'=>$teacher_->signature,'teacher_number'=>$teacher_->teacher_number,'admission_date'=>$teacher_->admission_date,'is_director'=>$teacher_->is_director,'director_classes'=>$teacher_->director_classes,'session'=>$session);
                $db = \Config\Database::connect();
                $builder = $db->table('teachers');
                if ($builder->insert($to_db)) {
                    $teacher_id = $db->insertID();
                    $return = TRUE;
                    $msg = "Teacher added successfully";
                } else {
                    $teacher_id = FALSE;
                    $return = FALSE;
                    $msg = "Failed to save teacher";
                }


                //Files
                if ($docs = (new Files())->where('uid', $teacher_id_)->where('type', 'teacher')->get()->getResult()) {
                    $file_ent = new File();
                    $file_model = new Files();
                    $file_ent->type = 'teacher';
                    $file_ent->uid = $teacher_id;
                    foreach ($docs as $doc) {
                        $file_ent->description = $doc->description;
                        $file_ent->file = $doc->file;
                        @$file_model->save($file_ent);
                    }
                }

                if ($return) {
                    return $teacher_id;
                }

                return $teacher_id;
    }

    public function updateTeacher($id)
    {
        $ionAuth = new \App\Libraries\IonAuth();
        $validation = \Config\Services::validation();
        $validation2 = \Config\Services::validation();
        $request = \Config\Services::request();
        // Create teacher user
        $teacher = [
            'surname'       => $request->getPost('surname'),
            'first_name'    => $request->getPost('first_name'),
            'last_name'     => $request->getPost('last_name'),
            'gender'        => $request->getPost('gender'),
            'phone'         => $request->getPost('phone_mobile'),
        ];
        $username = $request->getPost('phone_mobile');
        $password = random_string('alnum');
        $email = $request->getPost('phone_mobile').'@'.email_domain();

        $validation->reset();
        $validation->setRule('profile_pic', 'Profile Image', 'uploaded[profile_pic]|is_image[profile_pic]');
        $validation2->setRule('signature', 'Signature Image', 'uploaded[signature]|is_image[signature]');

        if($validation->withRequest($request)->run()) {
            $file = $request->getFile('profile_pic');
            $newName = $file->getRandomName();
            if($file->move(FCPATH.'uploads/avatars/', $newName)) {
                $teacher['avatar'] = $newName;
            }
        }

        $sig = '';
        if(is_file($request->getFile('signature'))) {
            $file2 = $request->getFile('signature');
            $newName2 = $file2->getRandomName();
            $sig = $newName2;
            if($file2->move(FCPATH.'uploads/', $newName2)) {
                $teacher['signature'] = $newName2;
            }

        }

        $the_teacher = $this->find($id);

        if($tId = $ionAuth->update($the_teacher->user_id, $teacher)) {
            $user = new \App\Entities\User();
            
            $user->id = $the_teacher->user_id;
            $user->update_usermeta('subcity', $request->getPost('subcity'));
            $user->update_usermeta('dob', $request->getPost('dob'));
            $user->update_usermeta('phone_mobile', $request->getPost('phone_mobile'));
            $user->update_usermeta('phone_work', $request->getPost('phone_work'));
            $user->update_usermeta('woreda', $request->getPost('woreda'));
            $user->update_usermeta('house_number', $request->getPost('house_number'));
            $user->update_usermeta('previous_school', $request->getPost('previous_school'));
            $user->update_usermeta('known_diseases', $request->getPost('known_diseases'));
            $user->update_usermeta('known_disabilities', $request->getPost('known_disabilities'));

            $teacher = $the_teacher;
            $teachers_model = new Teachers();
            $teacher->user_id = $user->id;
            //$teacher->teacher_number = formatTeacherNumber($tId);
            $teacher->admission_date = $request->getPost('admission_date');

            if (isset($_POST['is_director']) && $_POST['is_director'] ==1){
                $teacher->is_director = 1;
                $teacher->director_classes = json_encode($_POST['director_classes']);
            }else {
                $teacher->is_director = 0;
                $teacher->director_classes = null;
            }

            //Contact
            $contact = $teacher->contact;
            if($contact){
                $contact->surname = $request->getPost('e_surname');
                $contact->first_name = $request->getPost('e_first_name');
                $contact->last_name = $request->getPost('e_last_name');
                $contact->phone_mobile = $request->getPost('e_phone_mobile');
                $contact->phone_work = $request->getPost('e_phone_work');
                $contact->subcity = $request->getPost('e_subcity');
                $contact->woreda = $request->getPost('e_woreda');
                $contact->house_number = $request->getPost('e_house_number');
                $contact_model = new Contacts();

                if($contact->hasChanged()) {
                    $contact_model->save($contact);
                    //$teacher->contact = $contact_model->getInsertID();
                }
            }

            if($the_teacher->id && $docs = $request->getFiles()) {
                $labels = $request->getPost('title');
                $file_ent = new File();
                $file_model = new Files();
                $file_ent->type = 'teacher';
                $file_ent->uid = $the_teacher->id;
                $i = 0;
                foreach ($docs['doc'] as $doc) {
                    if ($doc->isValid() && ! $doc->hasMoved())
                    {
                        $newName = $doc->getRandomName();
                        if($doc->move(FCPATH.'uploads/files/', $newName)){
                            $file_ent->description = $labels[$i];
                            $file_ent->file = $newName;

                            @$file_model->save($file_ent);
                        }
                    }
                    $i++;
                }
            }

            $teacher->signature = $sig;
            $teacher->session = active_session();

            if($teacher->hasChanged()) {
                if($teachers_model->save($teacher)) {
                    $return = TRUE;
                    $msg = "Teacher updated successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to update teacher";
                }
            } else {
                $return = TRUE;
                $msg = "Teacher updated successfully";
            }
        } else {
            $return = FALSE;
            $msg = "Failed to update the teacher";
        }

        if($return) {
            return true;
        }

        return $msg;
    }
}
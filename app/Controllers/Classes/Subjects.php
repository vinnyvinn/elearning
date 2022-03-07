<?php


namespace App\Controllers\Classes;


use App\Controllers\ProfileController;
use App\Entities\Assignment;
use App\Entities\Note;
use App\Entities\Subject;
use App\Entities\Subjectteacher;
use App\Libraries\Pdf;
use App\Models\Assignments;
use App\Models\ClassSubjects;
use App\Models\LessonPlan;
use App\Models\Notes;
use App\Models\Sections;
use App\Models\Subjectteachers;
use CodeIgniter\Exceptions\PageNotFoundException;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Subjects extends ProfileController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Subjects";
        return $this->_renderPageCustom('Classes/Subjects/index', $this->data);
    }

    public function view($subject, $section)
    {
        $section = (new \App\Models\Sections())->find($section);
        $subject = (new ClassSubjects())->where('class', $section->class->id)->find($subject);
        //$subject = (new \App\Models\ClassSubjects())->where('class', $section->class->id)->get()->getCustomRowObject(1, '\App\Entities\ClassSubject');
        //$subject = (new \App\Models\ClassSubjects())->where('class', $section->class->id)->;

        //dd($subject);

        //if(!$subject || !$section || !$class) return new PageNotFoundException();

        $this->data['subject'] = $subject;
        $this->data['section'] = $section;
        $this->data['title'] = 'Overview';
        $this->data['page'] = 'overview';

        return $this->_renderSection('Classes/Subjects/view', $this->data);
    }

    public function unassignSubject($subject)
    {

        $model = new Subjectteachers();
            if($model->delete($subject)) {
                $return = TRUE;
                $msg = "Subject unassigned successfully";
            } else {
                $return = FALSE;
                $msg = "An error occurred";
            }


        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return ? 'window.location = "'.site_url(route_to('admin.users.teachers')).'"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    /**
     * Render sections of the class view layout
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    public function _renderSection($view, $data = [])
    {
        $html = view($view, $data);
        $dt = [
            'html' => $html
        ];
        $data = array_merge($data, $dt);
        return $this->_renderPage('Classes/Subjects/layout', $data);
    }
    public function print()
    {
        $this->data['subjects'] = (new \App\Models\Subjects())->findAll();
        return view('Classes/Subjects/list/print', $this->data);
    }
    function exportExcel()
    {
        $subjects = (new \App\Models\Subjects())->findAll();

        $file_name = 'Subject List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Subject List";
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

        // column headers
        $sheet->setCellValue('A2', 'Name');

        $count = 3;
        foreach($subjects as $row)
        {
            $sheet->setCellValue('A' . $count, $row->name);
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

    public function notes($subject, $section)
    {
        $section = (new \App\Models\Sections())->find($section);
        //$subject = (new \App\Models\ClassSubjects)->find($subject);
        $subject = (new ClassSubjects())->where('class', $section->class->id)->find($subject);
        //dd($subject);
        //Upload notes
        if ($data = $this->request->getPost()) {
            $model = new Notes();
            $entity = new Note();
            $file = $this->request->getFile('file');

            if ($file && $file->isValid() && !$this->request->getPost('id')) {
                $entity->fill($data);
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/notes', $newName);
                $entity->file = $newName;
                if ($model->save($entity)) {
                    $success = TRUE;
                    $msg = "Notes uploaded successfully";
                } else {
                    $success = FALSE;
                    $msg = implode('<br/>', $model->errors());
                }
            } elseif ($the_id = $this->request->getPost('id')) {
                if ($file && $file->isValid()) {
                    $entity->fill($data);
                    @unlink($model->find($the_id)->path);
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/notes', $newName);
                    $entity->file = $newName;
                    if ($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                } else {
                    $entity->fill($data);
                    unset($entity->file);
                    if ($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                }
            } else {
                $success = FALSE;
                $msg = $file->getErrorString();
            }
            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $success ? 'success' : 'error',
                    'title' => 'Notes Upload',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $success ? 'window.location.reload()' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(previous_url())->withInput();
            }
        }

        $this->data['subject'] = $subject;
        $this->data['section'] = $section;
        $this->data['title'] = 'Notes';
        $this->data['page'] = 'notes';

        return $this->_renderSection('Classes/Subjects/notes', $this->data);
    }

    public function createLessonPlan($section, $subject)
    {
        $section = (new \App\Models\Sections())->find($section);
        $subject = (new ClassSubjects())->find($subject);
        if (!$section || !$subject) {
            $return = FALSE;
            $msg = "Invalid request";
            return $this->response->redirect(site_url(route_to('admin.subjects.view', $subject->id, $section->id)));
        }
        if ($this->request->getPost()) {
            if (true) {
                $to_db = [
                    'session' => active_session(),
                    'class' => $section->class->id,
                    'section' => $section->id,
                    'subject' => $subject->id,
                    'month' => $this->request->getPost('month'),
                    'week' => $this->request->getPost('week'),
                    'unit' => $this->request->getPost('unit'),
                    'duration' => $this->request->getPost('duration'),
                    'objectives' => $this->request->getPost('objective'),
                    'day' => json_encode($this->request->getPost('day')),
                    'intro' => json_encode($this->request->getPost('intro')),
                    'presentation' => json_encode($this->request->getPost('presentation')),
                    'stabilization' => json_encode($this->request->getPost('stabilization')),
                    'evaluation' => json_encode($this->request->getPost('evaluation')),
                ];
                $model = new LessonPlan();
                $id = $this->request->getPost('id');
                if ($id && is_numeric($id)) {
                    $to_db['id'] = $id;
                }
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Lesson plan saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save lesson plan";
                }
            }
            $success = $return;
            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $success ? 'success' : 'error',
                    'title' => 'Success',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $success ? 'window.location = "' . site_url(route_to('admin.subjects.view', $subject->id, $section->id)) . '"' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(site_url(route_to('admin.subjects.view', $subject->id, $section->id)));
            }
        } else {
            $this->data['subject'] = $subject;
            $this->data['section'] = $section;
            $this->data['month'] = @$month ? $month : '';
            $this->data['week'] = @$week ? $week : '';
            $this->data['title'] = 'Overview';
            $this->data['page'] = 'overview';
            $this->_renderSection('Classes/Subjects/lesson_plan/create', $this->data);
        }
    }

    public function updateLessonPlan($section, $subject, $week, $month)
    {
        $section = (new \App\Models\Sections())->find($section);
        $subject = (new ClassSubjects())->find($subject);
        if (!$section || !$subject) {
            $return = FALSE;
            $msg = "Invalid request";
            return $this->response->redirect(site_url(route_to('admin.subjects.view', $subject->id, $section->id)));
        }
        if ($this->request->getPost()) {
            if (true) {
                $to_db = [
                    'session' => active_session(),
                    'class' => $section->class->id,
                    'section' => $section->id,
                    'subject' => $subject->id,
                    'month' => $this->request->getPost('month'),
                    'week' => $this->request->getPost('week'),
                    'unit' => $this->request->getPost('unit'),
                    'duration' => $this->request->getPost('duration'),
                    'objectives' => $this->request->getPost('objective'),
                    'day' => json_encode($this->request->getPost('day')),
                    'intro' => json_encode($this->request->getPost('intro')),
                    'presentation' => json_encode($this->request->getPost('presentation')),
                    'stabilization' => json_encode($this->request->getPost('stabilization')),
                    'evaluation' => json_encode($this->request->getPost('evaluation')),
                ];
                $model = new LessonPlan();
                $id = $this->request->getPost('id');
                if ($id && is_numeric($id)) {
                    $to_db['id'] = $id;
                }
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Lesson plan saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save lesson plan";
                }
            }
            $success = $return;
            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $success ? 'success' : 'error',
                    'title' => 'Success',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $success ? 'window.location = "' . site_url(route_to('admin.subjects.view', $subject->id, $section->id)) . '"' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(site_url(route_to('admin.subjects.view', $subject->id, $section->id)));
            }
        } else {
            $this->data['subject'] = $subject;
            $this->data['section'] = $section;
            $this->data['month'] = @$month ? $month : '';
            $this->data['week'] = @$week ? $week : '';
            $this->data['title'] = 'Overview';
            $this->data['page'] = 'overview';
            $this->_renderSection('Classes/Subjects/lesson_plan/update', $this->data);
        }
    }

    //Download lesson plan
    public function downloadLessonPlan($section, $subject, $week, $month)
    {
        //return redirect()->to(previous_url())->with('error', "Not implemented");

        //$section = $this->request->getPost('section');
        //$subject = $this->request->getPost('subject');
        $subject = (new ClassSubjects())->find($subject);
        $section = (new Sections())->find($section);
        if(!$section || !$subject) {
            return new PageNotFoundException('Class or Section not found');
        }
        $data = [
            'section' => $section,
            'subject'   => $subject,
            'month'     => $month,
            'week'      => $week
        ];

        $html = view('Academic/LessonPlan/view', $data);
//        $pdf = new Pdf();
//
//        try {
//            $pdf->WriteHTML($html);
//            $pdf->title = "https://www.bennito254.com/";
//            $name = 'Lesson-Plan_'.$subject->name.'-'.time().'.pdf';
//            $pdf->Output($name, Destination::DOWNLOAD);
//        } catch (\Exception $e) {
//            throw new \ErrorException($e->getMessage());
//        }
        ?>
        <html lang="en">
            <head>
                <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css">
                <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css">
            </head>
            <body style="">
                <?php echo $html; ?>
                <script>
                    window.print();
                </script>
            </body>
        </html>
        <?php
    }

    public function delete_note($id)
    {
        $model = new Notes();
        $note = $model->find($id);
        if ($note && $model->delete($note->id)) {
            @unlink($note->path);
            $s = TRUE;
            $msg = "File deleted successfully";
        } else {
            $s = FALSE;
            $msg = "Failed to delete file";
        }

        $s = $s ? 'success' : 'error';
        $this->session->setFlashData($s, $msg);

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => 'Delete Notes',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => 'window.location.reload()'
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }

    public function assignments($subject, $section)
    {
        $section = (new \App\Models\Sections())->find($section);
        //$subject = (new \App\Models\ClassSubjects())->find($subject);
        $subject = (new ClassSubjects())->where('class', $section->class->id)->find($subject);
        //dd($subject);
        //Upload assignment
        if ($data = $this->request->getPost()) {
            $model = new Assignments();
            $entity = new Assignment();
            $file = $this->request->getFile('file');

            if ($file && $file->isValid() && !$this->request->getPost('id')) {
                $entity->fill($data);
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/assignments', $newName);
                $entity->file = $newName;
                if ($model->save($entity)) {
                    $success = TRUE;
                    $msg = "Notes uploaded successfully";
                } else {
                    $success = FALSE;
                    $msg = implode('<br/>', $model->errors());
                }
            } elseif ($the_id = $this->request->getPost('id')) {
                if ($file && $file->isValid()) {
                    $entity->fill($data);
                    @unlink($model->find($the_id)->path);
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/assignments', $newName);
                    $entity->file = $newName;
                    if ($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                } else {
                    $entity->fill($data);
                    unset($entity->file);
                    if ($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                }
            } else {
                $success = FALSE;
                $msg = $file->getErrorString();
            }
            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $success ? 'success' : 'error',
                    'title' => 'Assignment Upload',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $success ? 'window.location.reload()' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(current_url())->withInput();
            }
        }

        $this->data['subject'] = $subject;
        $this->data['section'] = $section;
        $this->data['title'] = 'Assignments';
        $this->data['page'] = 'assignments';

        return $this->_renderSection('Classes/Subjects/assignments', $this->data);
    }

    public function delete_assignment($id)
    {
        $model = new Assignments();
        $note = $model->find($id);
        if ($note && $model->delete($note->id)) {
            @unlink($note->path);
            $s = TRUE;
            $msg = "Assignment deleted successfully";
        } else {
            $s = FALSE;
            $msg = "Failed to delete assignment";
        }

        $s = $s ? 'success' : 'error';
        $this->session->setFlashData($s, $msg);

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => 'Delete Assignment',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => 'window.location.reload()'
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }

    public function create()
    {
        $model = new \App\Models\Subjects();
        if ($model->save($this->request->getPost())) {
            $id = $model->getInsertID();
            do_action('subject_created', $id);
            $msg = "Subject created successfully";
            $return = true;
        } else {
            $return = false;
            $msg = $model->errors() ? implode('<br/>', $model->errors()) : 'Failed to create subject';
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'notifyType' => 'toastr',
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $return ? 'success' : 'error',
                'callback' => $return ? 'window.location.reload()' : false
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $status = $return ? 'success' : 'error';
        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url())->withInput();
    }

    public function update()
    {
        $model = new \App\Models\Subjects();
        $entity = new Subject();
        $entity->fill($this->request->getPost());
        if ((bool)$this->request->getPost('optional')) {
            $entity->optional = 1;
        } else {
            $entity->optional = 0;
        }
        if ($model->save($entity)) {
            do_action('subject_updated', $entity->id);
            $msg = "Subject updated successfully";
            $return = true;
        } else {
            $return = false;
            $msg = $model->errors() ? implode('<br/>', $model->errors()) : 'Failed to update subject';
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'notifyType' => 'toastr',
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $return ? 'success' : 'error',
                'callback' => $return ? 'window.location.reload()' : false
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $status = $return ? 'success' : 'error';
        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url())->withInput();
    }

    public function add_teacher($id)
    {
        if ($data = $this->request->getPost()) {
            $model = new Subjectteachers();
            $entity = new Subjectteacher();
            $data['session'] = active_session();
            $entity->fill($data);
            if ($model->save($entity)) {
                $return = true;
                $msg = "Changes updated successfully";
            } else {
                $return = false;
                $msg = "Failed to record changes";
            }
        } else {
            $return = false;
            $msg = "Invalid request";
        }
        if ($this->request->isAJAX()) {
            $resp = [
                'notifyType' => 'toastr',
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $return ? 'success' : 'error',
                'callback' => $return ? 'window.location.reload()' : false
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $status = $return ? 'success' : 'error';
        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url())->withInput();
    }

    public function delete($id)
    {
        $model = new \App\Models\Subjects();
        if ($model->delete($id)) {
            do_action('subject_deleted', $id);
            $msg = "Subject deleted successfully";
            $return = true;
        } else {
            $return = false;
            $msg = $model->errors() ? implode('<br/>', $model->errors()) : 'Failed to update subject';
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'notifyType' => 'toastr',
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $return ? 'success' : 'error',
                'callback' => $return ? 'window.location.reload()' : false
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

       $status = $return ? 'success' : 'error';
       $this->session->setFlashData($status, $msg);
       return $this->response->redirect(previous_url())->withInput();
    }

    public function pdf()
    {
       $this->data['subjects'] = (new \App\Models\Subjects())->findAll();
       return view("Classes/Subjects/list/pdf",$this->data);
    }
}
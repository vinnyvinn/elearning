<?php


namespace App\Controllers\Classes;


use App\Controllers\ProfileController;
use App\Models\Timetable;
use Config\Database;

class Sections extends ProfileController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function view($id)
    {
        $model = new \App\Models\Sections();
        $section = $model->find($id);

        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'overview',
            'title' => 'Overview'
        ];
        return $this->_renderSection('Classes/Sections/view', $data);
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
        return $this->_renderPage('Classes/Sections/layout', $dt);
    }

    public function students($id)
    {
        $model = new \App\Models\Sections();
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'students',
            'title' => 'Students'
        ];

        return $this->_renderSection('Classes/Sections/students', $data);
    }

    public function timetable($id)
    {
        $model = new \App\Models\Sections();
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'timetable',
            'title' => 'Timetable'
        ];
        return $this->_renderSection('Classes/Sections/timetable', $data);
    }

    public function createTimetable($id)
    {
        $model = new \App\Models\Sections();
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        if ($this->request->getPost()) {
            //Save the timetable
            $data = $this->request->getPost('timetable');
            $builder = Database::connect()->table('timetable');
            $class = $section->class->id;
            $builder->where('class',$class)->where('section',$id)->delete();
            foreach ($data as $day => $times) {
                foreach ($times as $time => $subject) {
                    if ($subject && $subject != '' && is_numeric($subject)) {
                        if ($existing = $builder->where(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time])->get()->getRow()) {
                            if ($existing->subject != $subject) {
                                $builder->resetQuery();
                                $update = $builder->where(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time])->update(['subject' => $subject]);
                                if ($update) {
                                    $success = true;
                                } else {
                                    $success = false;
                                }
                            }
                        } else {
                            $builder->resetQuery();
                            $insert = $builder->insert(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time, 'subject' => $subject]);
                            if ($insert != false) {
                                $success = true;
                            } else {
                                $success = false;
                            }
                        }
                    }
                }
            }

            if ($this->request->isAJAX()) {
                $resp = [
                    'title' => 'Success',
                    'message' => 'Timetable updated successfully',
                    'notifyType' => 'toastr',
                    'status' => 'success',
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $this->session->setFlashData('success', 'Timetable updated successfully');
                return $this->response->redirect(site_url(route_to('admin.class.sections.timetable', $section->id)));
            }
        }
        $data = [
            'section' => $section,
            'page' => 'timetable',
            'title' => 'Create Timetable'
        ];
        return $this->_renderSection('Classes/Sections/create_timetable', $data);
    }


    public function createAspTimetable($id)
    {
        $model = new \App\Models\Sections();
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        if ($this->request->getPost()) {
            //Save the timetable
            $data = $this->request->getPost('timetable');
            $builder = Database::connect()->table('asp_schedule');
            $class = $section->class->id;
            $builder->where('class',$class)->where('section',$id)->delete();
            foreach ($data as $day => $times) {
                foreach ($times as $time => $subject) {
                    if ($subject && $subject != '' && is_numeric($subject)) {
                        if ($existing = $builder->where(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time])->get()->getRow()) {
                            if ($existing->subject != $subject) {
                                $builder->resetQuery();
                                $update = $builder->where(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time])->update(['subject' => $subject]);
                                if ($update) {
                                    $success = true;
                                } else {
                                    $success = false;
                                }
                            }
                        } else {
                            $builder->resetQuery();
                            $insert = $builder->insert(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time, 'subject' => $subject]);
                            if ($insert != false) {
                                $success = true;
                            } else {
                                $success = false;
                            }
                        }
                    }
                }
            }

            if ($this->request->isAJAX()) {
                $resp = [
                    'title' => 'Success',
                    'message' => 'Timetable updated successfully',
                    'notifyType' => 'toastr',
                    'status' => 'success',
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $this->session->setFlashData('success', 'Timetable updated successfully');
                return $this->response->redirect(site_url(route_to('admin.class.sections.asp_schedule', $section->id)));
            }
        }
        $data = [
            'section' => $section,
            'page' => 'timetable',
            'title' => 'Create Timetable'
        ];
        return $this->_renderSection('Classes/Sections/asp_timetable', $data);
    }

    public function aspTimetable($id) //section ID
    {
        $model = new \App\Models\Sections();
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'asp_schedule',
            'title' => 'After School Program'
        ];
        return $this->_renderSection('Classes/Sections/view_asp_schedule', $data);
    }

    public function create()
    {
        $model = new \App\Models\Sections();
        $requests = $this->request->getPost();
        $requests['session'] = active_session();
        if ($model->save($requests)) {
            $id = $model->getInsertID();
            do_action('section_created', $id);
            $msg = "Section created successfully";
            $return = true;
        } else {
            $return = false;
            $msg = $model->errors() ? implode('<br/>', $model->errors()) : 'Failed to create section';
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

    public function update($id)
    {
        $model = new \App\Models\Sections();
        if ($model->save($this->request->getPost())) {
            do_action('section_updated', $id);
            $msg = "Section updated successfully";
            $return = true;
        } else {
            $return = false;
            $msg = $model->errors() ? implode('<br/>', $model->errors()) : 'Failed to update section';
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
        $model = new \App\Models\Sections();
        if ($model->delete($id)) {
            do_action('section_deleted', $id);
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

    public function createTimeslots($section)
    {
        $slots = $this->request->getJSON();

        $slots = $slots->slots;

        if(update_option('timetable_framework_'.$section, json_encode($slots))) {
            $return = TRUE;
            $msg = "Time slots updated successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to update time slots";
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
}
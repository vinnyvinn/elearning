<?php


namespace App\Controllers\Students;



use App\Models\Classes;
use App\Models\Sections;
use App\Models\User;

class Admission extends Students
{
    public function __construct()
    {
        parent::__construct();
    }

    public function admissionForm($id)
    {
        $student = (new \App\Models\Students())->find($id);

        $dompdf = new \Pdf();
        $dompdf->loadHtml(view('Admin/Students/pdf',['student'=>$student]));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream("",array('Attachment'=>false));
        exit();
    }

    public function admissionFormBulk($class,$section)
    {
        $model = (new \App\Models\Students())->where('session',active_session());
        if ($class !='all')
            $model->where('class',$class);
        if ($section !=0)
            $model->where('section',$section);
        $students = $model->findAll();

        $dompdf = new \Pdf();
        $dompdf->loadHtml(view('Admin/Students/bulk_pdf',['students'=>$students]));
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream("",array('Attachment'=>false));
        exit();
    }
    public function studentListPdf($class,$section)
    {
        $model = (new \App\Models\Students())->where('session',active_session());
        if ($class !='all')
            $model->where('class',$class);
        if ($section !=0)
            $model->where('section',$section);
        $students = $model->findAll();
       $this->data['students'] = $students;
       $this->data['class'] = $class !='all' ? (new Classes())->find($class)->name: '';
       $this->data['section'] = $section !=0 ? (new Sections())->find($section)->name :'';
        $phones = get_option('id_phone') ? json_decode(get_option('id_phone')) :'';
        $phones = $phones ? implode(' , ',$phones) : '';
        $this->data['phones'] = $phones;

        return view('Admin/Students/list/pdf', $this->data);
    }

    public function admissionID($id)
    {

        $student = (new \App\Models\Students())->find($id);
        $user = (new User())->find($id);


        return view('Admin/Students/id_pdf', ['student' => $student,'user'=>$user]);
    }
    public function admissionIDPrint($id)
    {
        $student = (new \App\Models\Students())->find($id);
        $user = (new User())->find($id);


        return view('Admin/Students/id_print', ['student' => $student,'user'=>$user]);
    }
    public function admissionIDPrintBulk($class,$section=null)
    {
        $model = (new \App\Models\Students())->where('session',active_session());
        if ($class !='all')
            $model->where('class',$class);
        if ($section !=0)
            $model->where('section',$section);

        $this->data['students'] =  $model->findAll();
      if (count($this->data['students']) <=6)
        return view('Admin/Students/six/bulk_ids', $this->data);
        return view('Admin/Students/bulk_ids', $this->data);
    }
    public function admissionCompact($class,$section=null)
    {
        $model = (new \App\Models\Students())->where('session',active_session());
        if ($class !='all')
            $model->where('class',$class);
        if ($section !=0)
            $model->where('section',$section);

        $this->data['students'] =  $model->findAll();

        return view('Admin/Students/compact_forms', $this->data);
    }
    public function admissionIDPdfBulk($class,$section)
    {
        $model = (new \App\Models\Students())->where('session',active_session());
        if ($class !='all')
            $model->where('class',$class);
        if ($section !=0)
            $model->where('section',$section);

        $this->data['students'] =  $model->findAll();

        if (count($this->data['students']) <=6)
        return view('Admin/Students/six/bulk_ids_pdf', $this->data);
        return view('Admin/Students/bulk_ids_pdf', $this->data);
    }
}
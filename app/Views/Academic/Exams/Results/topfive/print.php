<?php

use App\Models\ExamResults;
use CodeIgniter\Model;

$class = (new \App\Models\Classes())->find($class);
$exam = (new \App\Models\Exams())->find($exam);
$section = (new \App\Models\Sections())->find($section);

if($exam && $section) {
$subjects = $class->subjects();
$students = $section->studentsActive;

$students_arr = array();
foreach ($students as $student) {
    foreach ($subjects as $subject) {
        $result = (new ExamResults())->select('SUM(mark) as subtotal')->where('student', $student->id)->where('class', $class->id)
            ->where('subject', $subject->id)->where('exam', $exam->id)->get()->getRowObject();

        // $big = $model->select('SUM(mark) as tt')->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $class->id])->get()->getLastRow();
        if (!empty($result)) {
            if (!isset($students_arr[$student->id])) {
                $students_arr[$student->id] = $result->subtotal;
            } else {
                $students_arr[$student->id] += $result->subtotal;
            }
        }
    }
}
$rank_students = array_rank($students_arr);
    $top5_arr = array();

foreach ($rank_students as $key => $value){
    if ($value ==1 || $value == 2 || $value == 3 || $value == 4 || $value == 5){
        array_push($top5_arr, array('id'=>$key,'rank'=>$value));
    }
}
    usort($top5_arr, function ($a, $b) {return $a['rank'] > $b['rank'];});
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap">
<!-- Icons -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/nucleo/css/nucleo.css'); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css">

<link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css">
    <style>
        .wrapper {
            overflow: hidden;
            position: relative;
        }

        .bg_image {
            opacity: 0.6;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }

        .content {
            position: relative;
        }
        .fs30{
            font-size: 30px !important;
        }
    </style>
<script>
    window.print();
    setTimeout(() => {
        window.history.back();
    },3000)
</script>
    <div class="wrapper fs30">
        <div  class="content container-fluid">
             <?php
            $file = get_option('student_background_image')? base_url('uploads/'.get_option('student_background_image')) : base_url('assets/images/car.jpg');
            ?>
            <img src="<?php echo $file;?>" alt="" class="bg_image">
              <div class="row" style="padding-top: 5%">
                <div class="col-md-3">
                    <?php $file = get_option( 'website_logo'); ?>
                    <a href="<?php echo site_url(); ?>">
                        <img class="logo-dark" alt="Aspire School Logo"
                             src="<?php echo get_logo(); ?>" style="width:150px; !important;">
                    </a>
                </div>
                <div class="col-md-9">
                    <table style="text-decoration: underline">
                        <tr>
                            <th><h1 style="font-size: 30px"><?php echo get_option('id_school_name');?></h1></th>
                        </tr>
                        <tr>
                        <th style="margin-right: -5%"><h1><?php echo getSession()->name.' , '.$exam->name,', '.$students[0]->class->name.' - '.$students[0]->section->name;?></h1></th>
                        </tr>
                        <tr>
                            <th><h1 style="font-size: 30px">Top 5 Students</h1></th>
                        </tr>
                    </table>
                </div>
            </div>
        <div class="row" style="margin-top: 2%">
            <?php foreach ($top5_arr as $key => $value):
                $student = (new \App\Models\Students())->find($value['id']);
                $model = new \App\Models\ExamResults();
                $big = $model->select('SUM(mark) as tt')->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $class->id])->get()->getRowObject();
                $tt = $big->tt;
                $total_marks = 0;
                $i=0;
                foreach ($student->class->subjects as $subject) {
                    $rs = $model->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $student->class->id, 'subject' => $subject->id])->get()->getRowObject();
                    ?>
                    <?php
                    if ($subject->optional !=1) {
                        $i++;
                        $total_marks += (!empty($rs->mark) && is_numeric($rs->mark)) ? $rs->mark : 0;
                    }
                }

                ?>
                <div class="col-md-3">
                    <table>
                        <tr>
                            <td>
                                <img src="<?php echo $student->profile->avatar?$student->profile->avatar : base_url("assets/images/avatar.jpeg")?>"  style="border-radius: 64px;border: 10px solid dodgerblue;width:200px;height: 220px">
                            </td>
                        </tr>
                        <tr>
                            <td><h2><?php echo $student->profile->name;?></h2></td>
                        </tr>
                        <tr>
                            <td class="text-center"><h2><?php echo number_format($total_marks/$i,2);?></h2></td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <h2><?php echo $value['rank'].'/'.count($student->section->students)?></h2>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<?php }?>
<?php

use App\Models\ExamResults;
use CodeIgniter\Model;

$class = (new \App\Models\Classes())->find($class);
$exam = (new \App\Models\Exams())->find($exam);
$section = (new \App\Models\Sections())->find($section);
if($exam && $section) {
$subjects = $class->subjects();
$students = $section->students;

$students_arr = array();
foreach ($students as $student) {
    foreach ($subjects as $subject) {
        $result = (new ExamResults())->select('SUM(mark) as subtotal')->where('student', $student->id)
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
?>
<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">

    <style>
        hr {
            color: #0000004f;
            margin-top: 5px;
            margin-bottom: 5px
        }
        td{
            padding-top: 3%;
            padding-bottom: 3%;
        }
        .table td, .table th{
            white-space: revert !important;
        }

        /*size: A4 landscape;*/
        /*margin: 2mm 2mm 2mm 2mm !imseportant;*/
        /*padding: 0 !important;*/
        /*size: 400mm 500mm;*/
        /*height: 100%;*/
        @media print {
            @page {
                size: A4 landscape;
                margin-top: 1mm !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-bottom: 0 !important;
                size: 400mm 320mm;
                height: 100%;

            }
            body {
                margin-top: 1mm !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-bottom: 0 !important;
            }
        }
        .cool td,.cool th{
            border: 1px solid !important;
        }

        .hr_report{
            display: block;
            height: 2px !important;
            background: transparent;
            width: 100%;
            border:1px solid #aaa !important;
            margin-top: 18px !important;
        }
        .fmbm{
            font-family: Bookman Old Style !important;
        }
        .fs18{
            font-size: 18px !important;
        }
        .fmcamb{
            font-family: Cambria, Georgia, serif;
        }
        .fs22{
            font-size: 22px !important;
        }
        .fs12{
            font-size: 20px !important;
        }

        .fs14{
         font-size: 20px !important;
        }
        .fs16{
          font-size: 16px !important;
        }
        .card .academic .vinn th{

            overflow-x: hidden;
        }
    </style>
    <title>Exam Results</title>
</head>
<body id="download">
<div id="pannation-project">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body" style="margin-left: 30%">
                <div class="row">
                    <div>
                        <table style="text-align: center">
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo get_option('id_school_name')?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo get_option('website_location');?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo $exam->name;?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900">Exam Results</b> </th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo $class->name.' '.$section->name;?> </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                if($students && count($students) > 0) {
                    ?>
                    <div class="table-responsive pt-2">
                        <table class="table" id="results_table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Section</th>
                                <?php
                                //  $subjects = $class->subjects();
                                foreach ($subjects as $subject) {
                                    // if ($subject->optional !=1){
                                    ?>
                                    <th><?php echo $subject->name; ?></th>
                                    <?php
                                    // }
                                }
                                ?>
                                <th>Average</th>
                                <th>Total</th>
                                <th>Rank</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            foreach ($rank_students as $student => $rank) {
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo getStudent($student); ?></td>
                                    <td><?php echo getSection($student); ?></td>
                                    <?php
                                    $i = 0;
                                    $tt = 0;
                                    $model = new \App\Models\ExamResults();
                                    $big = $model->select('SUM(mark) as tt')->where(['exam' => $exam->id, 'student' => $student])->get()->getRowObject();
                                    $tt = $big->tt;
                                    $total_marks = 0;
                                    foreach ($subjects as $subject) {
                                        //  if ($subject->optional !=1) {
                                        $rs = $model->where(['exam' => $exam->id, 'student' => $student, 'subject' => $subject->id])->get()->getRowObject();
                                        ?>
                                        <td><?php echo ($rs && !empty($rs->mark)) ? $rs->mark : '0'; ?></td>
                                        <?php
                                        if ($subject->optional !=1) {
                                            $i++;
                                            $total_marks += (!empty($rs->mark) && is_numeric($rs->mark)) ? $rs->mark : 0;
                                        }
                                        //}
                                    }
                                    ?>
                                    <td>
                                        <?php
                                        echo number_format(($total_marks/$i), 2);
                                        ?>
                                    </td>
                                    <td><?php echo  number_format($total_marks, 2); ?></td>
                                    <td><?php echo $rank; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-warning">
                        This class has no students
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    </div>
</body>

<script>
//
    window.print();
       setTimeout(() => {
           window.close();
       },3000)


</script>


    <?php
} else {
    ?>
    <div class="alert alert-danger">
        Invalid class section or exam selected
    </div>
    <?php
}
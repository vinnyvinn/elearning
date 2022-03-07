<?php

use App\Models\ExamResults;
use CodeIgniter\Model;

$class = (new \App\Models\Classes())->find($class);
$exam = (new \App\Models\Exams())->find($exam);
$section = (new \App\Models\Sections())->find($section);
if($exam && $section) {
$subjects = $class->subjects();
$students = $section->students;
$std = (new \App\Models\Students())->find(1948);
//echo '<pre>';
// var_dump($std->class->subjects);


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
                                <th><b style="font-size: 26px;font-weight: 900">Exam Results </b> </th>
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
                                <th style="font-size: 20px">#</th>
                                <th style="font-size: 20px">Student Name</th>
                                <th style="font-size: 20px">Section</th>
                                <?php
                                //  $subjects = $class->subjects();
                                foreach ($subjects as $subject) {
                                    // if ($subject->optional !=1){
                                    ?>
                                    <th style="font-size: 20px"><?php echo $subject->name; ?></th>
                                    <?php
                                    // }
                                }
                                ?>
                                <th style="font-size: 20px">Average</th>
                                <th style="font-size: 20px">Total</th>
                                <th style="font-size: 20px">Rank</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            foreach ($rank_students as $student => $rank) {
                                $n++;
                                ?>
                                <tr>
                                    <td style="font-size: 24px" ><?php echo $n; ?></td>
                                    <td style="font-size: 24px"><?php echo getStudent($student); ?></td>
                                    <td style="font-size: 24px"><?php echo getSection($student); ?></td>
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
                                        <td style="font-size: 24px"><?php echo ($rs && !empty($rs->mark)) ? $rs->mark : '0'; ?></td>
                                        <?php
                                        if ($subject->optional !=1) {
                                            $i++;
                                            $total_marks += (!empty($rs->mark) && is_numeric($rs->mark)) ? $rs->mark : 0;
                                        }
                                        //}
                                    }
                                    ?>
                                    <td style="font-size: 24px">
                                        <?php
                                        echo number_format(($total_marks/$i), 2);
                                        ?>
                                    </td>
                                    <td style="font-size: 24px"><?php echo  number_format($total_marks, 2); ?></td>
                                    <td style="font-size: 24px"><?php echo $rank; ?></td>
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

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = "Exam Results";

    var element = document.getElementById('pannation-project');
    var opt = {
        margin:       0,
        filename:     name+'.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { dpi: 800, letterRendering: true},
        jsPDF:        { unit: 'in', format: 'A0', orientation: 'portrait' }
    };

    // New Promise-based usage:
    //  html2pdf().set(opt).from(element).save();

    // Old monolithic-style usage:
    html2pdf(element, opt)
        .then(res =>{
            console.log('finished')
            setTimeout(()=>{
                window.history.back();
            },2000)

        })

</script>


    <?php
} else {
    ?>
    <div class="alert alert-danger">
        Invalid class section or exam selected
    </div>
    <?php
}
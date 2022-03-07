<?php
$section = (new \App\Models\Sections())->find($section);
$semester = (new \App\Models\Semesters())->find($semester);
$subjects = $section->class->subjects;
$students = $section->students;
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
    <title>Semester Ranking</title>
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
                                <th><b style="font-size: 26px;font-weight: 900">Semester Ranking</b> </th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo $section->class->name.' '.$section->name;?> </b> </th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo $semester->name;?> </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                //$class = (new \App\Models\Classes())->find($class);

                $students_arr = array();

                $resultsModel = new \App\Libraries\YearlyResults(79, active_session());
                $res_arr = array();
                foreach ($section->students as $student) {
                    $total_marks = 0;
                    $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());

                    if (count($subjects) > 0) {
                        foreach ($subjects as $subject) {
                            $result = $resultsModel->getSemesterTotalResultsPerSubject($semester->id, $subject->id,$section->id);
                            if (!empty($result) && is_numeric($result)) {
                                if (!isset($students_arr[$student->id])) {
                                    $students_arr[$student->id] = $subject->optional ==0 ? $result : 0;
                                } else {
                                    $students_arr[$student->id] += $subject->optional ==0 ? $result : 0;
                                }
                            }
                        }
                    }
                }

                $student_ranks = array_rank($students_arr);
                $counter = 0;
                ?>
                <div class="table-responsive">
                    <table class="table datatable" id="datatable">
                        <thead>
                        <tr>
                            <th># </th>
                            <th>Student</th>
                            <?php
                            if(count($subjects) > 0) {
                                foreach ($subjects as $subject) {
                                    if ($subject->optional == 0)
                                        $counter++;
                                    ?>
                                    <th><?php echo $subject->name; ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th>Average</th>
                            <th>Total</th>
                            <th>Rank</th>
                            <th style="display: none"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        $i = 0;

                        foreach ($student_ranks as $student => $rank) {
                            $n++;
                            $total_marks = 0;
                            $resultsModel = new \App\Libraries\YearlyResults($student, active_session());
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo getStudent($student); ?></td>
                                <?php
                                if(count($subjects) > 0) {
                                    foreach ($subjects as $subject) {
                                        $result = $resultsModel->getSemesterTotalResultsPerSubject($semester->id, $subject->id);
                                        ?>
                                        <td>
                                            <?php
                                            if($result && !empty($result) && $subject->optional == 0) {
                                                echo $result;
                                            }elseif($subject->optional ==0) {
                                                echo '-';
                                            }
                                            if ($subject->optional ==0){
                                                $i++;
                                                $total_marks += is_numeric($result) ? $result : 0;
                                            }

                                            if ($subject->optional == 1) {
                                                $res = (new \App\Models\ClassSubjects())->find($subject->id);
                                                if (!empty($res->grading) && $result) {
                                                    $grade = json_decode($res->grading);
                                                    foreach ($grade as $g) {
                                                        $item = explode('-', $g->scale);
                                                        if ($result >= min($item) && $result <= max($item)) {
                                                            echo $g->grade;
                                                            break;
                                                        }

                                                    }
                                                }else{
                                                    echo '-';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <?php
                                    }
                                }
                                ?>
                                <td>
                                    <?php echo number_format($total_marks/$counter, 2); ?>
                                </td>
                                <td><?php echo number_format($total_marks, 2); ?></td>
                                <td><?php echo $rank; ?></td>
                                <td style="display:none;"></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
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


<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>Semester Ranking</title>
</head>
<?php
$section = (new \App\Models\Sections())->find($section);
$semester = (new \App\Models\Semesters())->find($semester);
$subjects = $section->class->subjects;
$students = $section->students;
?>
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
                                <th><b style="font-size: 26px;font-weight: 900">Semester Ranking </b> </th>
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
                            <th style="font-size: 20px"># </th>
                            <th style="font-size: 20px">Student</th>
                            <?php
                            if(count($subjects) > 0) {
                                foreach ($subjects as $subject) {
                                    if ($subject->optional == 0)
                                        $counter++;
                                    ?>
                                    <th style="font-size: 20px"><?php echo $subject->name; ?></th>
                                    <?php
                                }
                            }
                            ?>
                            <th style="font-size: 20px">Average</th>
                            <th style="font-size: 20px">Total</th>
                            <th style="font-size: 20px">Rank</th>
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
                                <td style="font-size: 24px"><?php echo $n; ?></td>
                                <td style="font-size: 24px"><?php echo getStudent($student); ?></td>
                                <?php
                                if(count($subjects) > 0) {
                                    foreach ($subjects as $subject) {
                                        $result = $resultsModel->getSemesterTotalResultsPerSubject($semester->id, $subject->id);
                                        ?>
                                        <td style="font-size: 24px">
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
                                <td style="font-size: 24px">
                                    <?php echo number_format($total_marks/$counter, 2); ?>
                                </td>
                                <td style="font-size: 24px"><?php echo number_format($total_marks, 2); ?></td>
                                <td style="font-size: 24px"><?php echo $rank; ?></td>
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

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = 'Semester Ranking';

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



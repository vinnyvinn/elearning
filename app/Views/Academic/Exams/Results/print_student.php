<?php
?>
<html lang="en">
    <head>
        <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
        <title>Student Results</title>
    </head>
    <body>
        <div>
            <div class="row justify-content-center">
                <div class="col-md-4 align-content-center">
                    <h4 class="align-content-center"><?php echo $student->profile->name; ?></h4>
                    <h6 class="align-content-center"><?php echo $exam->name; ?></h6>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $model = new \App\Models\ExamResults();
                    $subjects = $student->class->subjects();
                    $big = $model->select('SUM(mark) as tt')->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $student->class->id])->get()->getLastRow();
                    $tt = $big->tt;
                    $n = 0;
                    foreach ($subjects as $subject) {
                        $n++;
                        $rs = $model->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $student->class->id, 'subject' => $subject->id])->get()->getLastRow();

                        if (isset($rs) && $subject->optional !=1){
                        ?>
                        <tr>
                            <th><?php echo $n; ?></th>
                            <td><?php echo $subject->name; ?></td>
                            <td><?php echo  $rs->mark ?></td>
                        </tr>
                        <?php
                    }}
                    ?>
                    <tr>
                        <th></th>
                        <th>TOTAL</th>
                        <th><?php echo $tt; ?></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Average</th>
                        <th><?php echo ($tt/$n); ?></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Rank</th>
                        <th><?php echo $model->getRank($student->id, $exam->id, $student->class->id).'/'.count($student->section->students) ?></th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    <script>
        window.print();
        setTimeout(()=>{
            window.close();
        },2000)

    </script>
</html>

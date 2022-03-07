<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css" media="all">
    <title>Teachers Attendance</title>
    <style>
        .red{
            background: red;
        }
        .blue{
            background: blue;
        }
        .yellow{
            background: yellow;
        }
        .text-green {
            color: #2dce89 !important;
        }
    </style>
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
                                <th><b style="font-size: 26px;font-weight: 900">Tel: 011-3216747, 011-3206688</b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 26px;font-weight: 900">Teachers Attendance </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                $start = $year . '-' . $month . '-01';
                $end = $year . '-' . $month . '-' . date('t', strtotime($start));

                $dates = [];
                while (strtotime($start) <= strtotime($end)) {
                    $day_num = date('d', strtotime($start));

                    $start = date("Y-m-d", strtotime("+1 day", strtotime($start)));

                    $dates[] = $year . '-' . $month . '-' . $day_num;
                }

                if ($teachers && count($teachers) > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="attend-table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <?php
                                foreach ($dates as $date) {
                                    ?>
                                    <th><?php echo date('d', strtotime($date)); ?></th>
                                    <?php
                                }
                                ?>
                                <th>P</th>
                                <th>A</th>
                                <th>Ps</th>
                                <th>S</th>
                                <th>L</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            $attendance = new \App\Models\Attendance();
                            foreach ($teachers as $teacher) {
                                $att = $attendance->where('timestamp', strtotime($date))->where('teacher', $teacher->trID)->get()->getRowObject();
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $teacher->surname.' '.$teacher->first_name.' '.$teacher->last_name; ?></td>
                                    <?php
                                    foreach ($dates as $date) {
                                        $att = $attendance->where('timestamp', strtotime($date))->where('teacher', $teacher->trID)->get()->getRowObject();
                                        ?>
                                        <td class="events <?php if ($att): if ($att->status == 5 && $att->option_type=='exams'):?>red <?php elseif ($att->status == 5 && $att->option_type=='weekend'):?>yellow <?php elseif ($att->status == 5 && $att->option_type=='events'):?>blue<?php endif;endif;?>">
                                            <?php

                                            if ($att) {
                                                if ($att->status == 1):
                                                    echo '<span class="text-green"><i class="fa fa-check-circle"></i></span>';
                                                elseif ($att->status == 0):
                                                    echo '<span class="text-danger">x</span>';
                                                elseif ($att->status == 2):
                                                    echo 'P';
                                                elseif ($att->status == 3):
                                                    echo 'S';
                                                elseif ($att->status == 4):
                                                    echo 'L';
                                                endif;
                                            }
                                            ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td><?php echo teacherInfo($teacher->trID,$month)['present'];?></td>
                                    <td><?php echo teacherInfo($teacher->trID,$month)['absent'];?></td>
                                    <td><?php echo teacherInfo($teacher->trID,$month)['permission'];?></td>
                                    <td><?php echo teacherInfo($teacher->trID,$month)['sick'];?></td>
                                    <td><?php echo teacherInfo($teacher->trID,$month)['late'];?></td>
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
                    <div class="card-body">
                        <div class="alert alert-danger">
                            No teachers found for this class
                        </div>
                    </div>
                    <?php
                }?>
            </div>
        </div>
    </div>
</div>
</body>

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = 'Teachers Attendance';

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



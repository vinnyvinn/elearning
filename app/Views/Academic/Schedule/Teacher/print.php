<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/nucleo/css/nucleo.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css">
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
    <title>Teacher's Schedule</title>
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
                             <th><b style="font-size: 30px;font-weight: 900"><?php echo get_option('id_school_name')?></b></th>
                            </tr>
                            <tr>
                             <th><b style="font-size: 30px;font-weight: 900"><?php echo get_option('website_location');?></b></th>
                            </tr>
                            <tr>
                             <th><b style="font-size: 30px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                             <th><b style="font-size: 30px;font-weight: 900">Teacher's Schedule </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php

                use App\Models\Timetable;
                ?>
                <?php
                $framework = get_option('timetable_framework_'.$class, get_option('timetable_framework', FALSE));
                $builder = new Timetable();
                //$class = $section->class->id;
                ?>

                <?php
                if ($framework) {
                    $framework = json_decode($framework, TRUE);
                    ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                            <tr>
                                <th style="font-size: 22px !important;">Day</th>
                                <?php
                                foreach ($framework as $time) {
                                    ?>
                                    <th style="font-size: 22px !important;"><?php echo '<b>' . $time['time'] . '</b>'; ?></th>
                                    <?php
                                }
                                ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $days = json_decode(get_option('school_days', json_encode(['Mon', 'Tue', 'Wed', 'Thur', 'Fri'])), true);
                            foreach ($days as $day) {
                                ?>
                                <tr>
                                    <th style="font-size: 24px !important;"><?php echo '<b>' . $day . '</b>'; ?></th>
                                    <?php
                                    foreach ($framework as $time) {
                                        ?>
                                        <td style="font-size: 24px !important;">
                                            <?php
                                            if ($time['break']) {
                                                echo '<b>' . $time['label'] . '</b>';
                                            } else {
                                                $ss = (new \App\Models\Subjectteachers())->select('subject_id')->where('teacher_id', $teacher->id)->findAll();
                                                $ss_temp = [];
                                                foreach ($ss as $s) {
                                                    $ss_temp[] = $s->subject_id;
                                                }
                                                $sub = $builder->whereIn('subject', $ss_temp)->where(['day' => $day, 'time' => $time['time']])->findAll();
                                                if ($sub && is_array($sub)) {
                                                    //d($sub);
                                                    foreach ($sub as $item) {
                                                        echo $item->subject->name;
                                                        echo "<br/><b>".$item->class->name."</b>, ";
                                                        echo "<b>".$item->section->name."</b>";
                                                    }
                                                } else {
                                                    echo '-';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
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
                        <div class="alert alert-warning">
                            Timetable is not set up
                        </div>
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
      window.print();
       setTimeout(() => {
           window.close();
       },3000)


</script>


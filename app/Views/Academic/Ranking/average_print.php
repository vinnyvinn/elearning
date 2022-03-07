<?php
$section = (new \App\Models\Sections())->find($section);
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
    <title>Semester Ranking- Yearly Average </title>
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
                                <th style="border: none !important;"><b style="font-size: 26px;font-weight: 900"><?php echo get_option('id_school_name')?></b></th>
                            </tr>
                            <tr>
                                <th style="border: none !important;"><b style="font-size: 26px;font-weight: 900"><?php echo get_option('website_location');?></b></th>
                            </tr>
                            <tr>
                                <th style="border: none !important;"><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                                <th style="border: none !important;"><b style="font-size: 26px;font-weight: 900">Semester Ranking- Yearly Average </b> </th>
                            </tr>
                            <tr>
                                <th style="border: none !important;"><b style="font-size: 26px;font-weight: 900"><?php echo $section->class->name.' '.$section->name;?> </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                $students = $section->students;
                ?>
                <style>
                    td,th{
                        border: 1px solid black !important;
                    }
                </style>
                <div class="table-responsive">
                    <?php
                    $semesters = (new \App\Models\Semesters())->where('session',active_session())->findAll();
                    $data = studentResult($students,$semesters[0]->id,$semesters[1]->id);
                    $keys = array_keys($data);
                    $stud = (new \App\Models\Students())->find(array_slice($keys,0,1));
                    $subjects = $stud[0]->class->subjects;
                    ?>

                    <table class="table datatable" id="">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Sex</th>
                            <th>Semester</th>
                            <?php foreach ($subjects as $subject):?>
                                <th><?php echo $subject->name;?></th>
                            <?php endforeach;?>
                            <th>Total</th>
                            <th>Average</th>
                            <th>Rank</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $key => $items):
                            $student = (new \App\Models\Students())->find($key);
                            ?>
                            <tr>
                            <td rowspan="4" style="vertical-align: middle"><?php echo $student->profile->name;?></td>
                            <td rowspan="4" style="vertical-align: middle"><?php echo strtolower($student->profile->gender)=='female' ? "F" : "M";?></td>
                            <?php foreach($items as $k2 => $item):
                            ?>
                            <tr>

                                <td><?php echo $k2;?></td>
                                <?php
                                foreach ($item['scores'] as $k3 => $val):?>
                                    <td><?php echo $val;?></td>
                                <?php endforeach;?>
                                <td><?php echo $item['total']?></td>
                                <td><?php echo $item['average']?></td>
                                <td><?php echo $item['rank']?></td>
                            </tr>
                        <?php endforeach;?>
                            </tr>
                        <?php endforeach; ?>
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


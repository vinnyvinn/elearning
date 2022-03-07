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
    <title>Event List</title>
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
                                <th><b style="font-size: 26px;font-weight: 900">Event List </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="events-basic">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Event</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Class</th>
                            <th>Section</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        $events = (new \App\Models\Events())
                            ->like('starting_date', '-'.$event_month.'-', 'both')
                            ->orLike('ending_date', '-'.$event_month.'-', 'both')
                            ->orLike('starting_date', '/'.$event_month.'/', 'both')
                            ->orLike('ending_date', '/'.$event_month.'/', 'both')
                            ->where('session',active_session())
                            ->orderBy('starting_date', 'ASC')->findAll();
                        foreach ($events as $event) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $event->name; ?></td>
                                <td><?php echo $event->starting_date->format('d/m/Y'); ?></td>
                                <td><?php echo $event->ending_date ? $event->ending_date->format('d/m/Y') : ''; ?></td>
                                <td><?php echo $event->class ? $event->class->name : ''; ?></td>
                                <td><?php echo $event->section ? $event->section->name : ''; ?></td>
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
      window.print();
       setTimeout(() => {
           window.close();
       },3000)


</script>


<html lang="en"
<head>
<link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
<link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
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

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = '<?php echo getSession()->name;?> Event List';

    var element = document.getElementById('pannation-project');
    var opt = {
        margin:       0,
        filename:     name+'.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { dpi: 800, letterRendering: true},
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
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



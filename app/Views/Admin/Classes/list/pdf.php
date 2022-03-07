<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>Teachers List</title>
</head>
<body id="download">
<div id="pannation-project">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
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
                            <th><b style="font-size: 26px;font-weight: 900">Class List </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Class</th>
                            <th colspan="3">Session</th>
                        </tr>
                        <?php foreach ($classes as $class):
                            if (count($class->sections) > 0):
                                ?>
                                <tr>
                                    <th><b><?php echo $class->name?></b></th>
                                    <th colspan="3"><b><?php echo getSession()->name;?></b></th>
                                </tr>
                                <tr>
                                    <th><b>Section </b></th>
                                    <th><b>Advisor</b></th>
                                    <th><b>Capacity</b></th>
                                    <th><b>No. of Students</b></th>
                                </tr>
                                <?php foreach ($class->sections as $section):?>
                                <tr>
                                    <td><?php echo $section->name;?></td>
                                    <td><?php echo $section->advisor->profile->name;?></td>
                                    <td><?php echo $section->maximum_students;?></td>
                                    <td><?php echo count($section->students)?></td>
                                </tr>
                            <?php endforeach;endif;endforeach;?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>
<script>
    var name = 'Class List';

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



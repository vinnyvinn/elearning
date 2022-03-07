<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css" media="all">
    <title>Teachers Monthly Attendance Counter</title>
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
            <div class="card-body">
                <div class="row">
                    <div>
                        <table style="text-align: center;margin-left: 30% !important;width: 100%">
                            <tr>
                             <th><b style="font-size: 18px;font-weight: 900"><?php echo get_option('id_school_name')?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 18px;font-weight: 900"><?php echo get_option('website_location');?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 18px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 18px;font-weight: 900">Teachers Monthly Attendance Counter</b> </th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 18px;font-weight: 900"><?php echo getMonthName($month)?></b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                if ($attendance && count($attendance) > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="attend-table">
                            <thead class="thead-light">
                            <tr>
                                <th>Date </th>
                                <th>No. of Present Teachers</th>
                                <th>No. of Absent Teachers</th>
                                <th>No. of Permission Teachers</th>
                                <th>No. of Sick Teachers</th>
                                <th>No. of Late Teachers</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($attendance as $item) {
                                ?>
                                <tr>
                                    <td><?php echo $item['date']; ?></td>
                                    <td><?php echo $item['teachers']-$item['absent']-$item['permission']-$item['sick']-$item['late'] .'/'.$item['teachers'];?></td>
                                    <td><?php echo $item['absent'] .'/'.$item['teachers']; ?></td>
                                    <td><?php echo $item['permission'] .'/'.$item['teachers']; ?></td>
                                    <td><?php echo $item['sick'] .'/'.$item['teachers']; ?></td>
                                    <td><?php echo $item['late'] .'/'.$item['teachers']; ?></td>
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
                            No attendance found for this class
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
    var name = 'Teachers Monthly Attendance Counter';

    var element = document.getElementById('pannation-project');
    var opt = {
        margin:       [0,-0.1,0,0],
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



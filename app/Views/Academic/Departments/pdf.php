<?php
$departments = getSession()->departments;
?>
<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>Departments</title>
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
                             <th><b style="font-size: 26px;font-weight: 900">Department List</b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th><b style="font-size: 22px">Department Name</b></th>
                        <th><b style="font-size: 22px">Head Of Department</b></th>
                    </tr>
                    <?php foreach ($departments as $department):?>
                        <tr>
                            <th><b style="font-size: 22px"><?php echo $department->name;?></b></th>
                            <th><b style="font-size: 22px"><?php echo $department->head->profile->name?></b></th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center" style="text-decoration: underline"><b style="font-size: 20px">Subjects in the department</b></th>
                        </tr>
                        <?php foreach ($department->subjects as $subject):?>
                            <tr>
                                <td colspan="2" class="text-center" style="font-size: 16px"><?php echo $subject->subject->name;?></td>
                            </tr>
                        <?php endforeach;endforeach;?>
                </table>
            </div>
        </div>
    </div>
    </div>
</body>

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = 'Department List';

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



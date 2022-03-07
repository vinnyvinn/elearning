<?php
$groups = getSession()->groups;
?>
<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>Students List</title>
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
                            <th><b style="font-size: 26px;font-weight: 900">Tel: <?php echo $phones;?></b></th>
                            </tr>
                            <tr>
                            <th><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                            <th><b style="font-size: 26px;font-weight: 900"><?php echo ($class && $section) ? $class.$section : 'ALL STUDENT'?></b> </th>
                            </tr>
                            <tr>
                            <th><b style="font-size: 26px;font-weight: 900">Students List </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Student Name</th>
                            <th>ADMN #</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Admission Date</th>
                        </tr>
                        <?php foreach ($students as $student):?>
                            <tr>
                                    <td><?php echo $student->profile->name;?></>
                                    <td><?php echo $student->admission_number?></td>
                                    <td><?php echo $student->class->name;?></td>
                                    <td><?php echo $student->section->name;?></td>
                                <td><?php echo $student->admission_date ? date('d/m/Y',strtotime($student->admission_date)) : ''; ?></td>
                                </tr>
                            <?php endforeach;?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>
<script>
    var name = '<?php echo  ($class && $section) ? 'Students List '.getSession()->year.' '.$class.$section : ' Students List '.getSession()->year.' ALL STUDENT'?>';

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



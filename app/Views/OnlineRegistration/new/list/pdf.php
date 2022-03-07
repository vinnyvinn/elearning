<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>New Student Registration</title>
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
                            <th><b style="font-size: 26px;font-weight: 900">New Registered Student List</b> </th>
                            </tr>
                            <tr>
                           <th><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                           <th><b style="font-size: 26px;font-weight: 900"><?php echo $grade;?></b></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="students-table">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>D.O.B</th>
                            <th>Class</th>
                            <th>Parent's Name</th>
                            <th>Parent's Contact</th>
                            <th>Application Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($students as $student) {
                            $n++;
                            $class = (new \App\Models\Classes())->find((int)$student->info->class);
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $student->name; ?></td>
                                <td><?php echo $student->dob; ?></td>
                                <td><?php echo isset($class->name) ? $class->name : ''; ?></td>
                                <td><?php echo @$student->parent->surname.' '.$student->parent->first_name.' '.$student->parent->last_name; ?></td>
                                <td><?php echo @$student->parent->mobile_number; ?></td>
                                <td><?php echo $student->created_at->format('d/m/Y h:i A'); ?></td>
                                <td>
                                    <?php if ($student->status =='pending'):?>
                                        <span class="badge badge-danger">Pending</span>
                                    <?php else:?>
                                        <span class="badge badge-primary">Registered</span>
                                    <?php endif;?>

                                </td>
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
    var name = 'New Student Registration List';

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



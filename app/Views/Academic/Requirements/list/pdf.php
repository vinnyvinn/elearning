<?php
$selected_class = \Config\Services::request()->getGet('class');
$selected_month = \Config\Services::request()->getGet('month');
?>
<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>Payments List</title>
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
                            <th><b style="font-size: 26px;font-weight: 900">Requirements List </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                $model = new \App\Models\Requirements();
                $model->where('session', active_session())->findAll();
                if($selected_class != '' && is_numeric($selected_class)) {
                    $model->where('class', $selected_class);
                }
                if($selected_month != '' && is_numeric($selected_month)) {
                    $selected_month = str_pad($selected_month, 2, '0', STR_PAD_LEFT);
                    $model->like('deadline', $selected_month, 'left');
                    //$model->where('payment_month', $selected_month);
                }
                $model->orderBy('id', 'DESC');
                $requirements = $model->findAll();
                if($requirements && count($requirements) > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table" id="requirements_table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Description</th>
                                <th>Item</th>
                                <th>Deadline</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            foreach ($requirements as $requirement) {
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $requirement->class ? $requirement->class->name : 'All Classes'; ?></td>
                                    <td><?php echo $requirement->section ? $requirement->section->name : 'All Sections'; ?></td>
                                    <td><?php echo $requirement->description; ?></td>
                                    <td><?php echo $requirement->item; ?></td>
                                    <td><?php echo $requirement->deadline; ?></td>
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
                    <div class="alert alert-warning">
                        No requirements have been added
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>
<script>
    var name = 'Requirements List';

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


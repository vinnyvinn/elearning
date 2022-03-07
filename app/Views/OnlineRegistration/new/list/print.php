<!DocType html>
<html lang="en">
<head>
<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap2.min.css'); ?>"/>
</head>
<body id="pannation-project">
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
                        <th><b style="font-size: 26px;font-weight: 900"> <?php echo get_option('website_location');?></b></th>
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

</body>
</html>

<script>
 window.print();
  setTimeout(() => {
      window.close();
  },3000)


</script>
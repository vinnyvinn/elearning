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
                            <th><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                        </tr>
                        <tr>
                         <th><b style="font-size: 26px;font-weight: 900">Administration Recruitment List </b> </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="teachers-table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>D.O.B</th>
                        <th>Contact</th>
                        <th>Application Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($teachers as $teacher) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $teacher->name; ?></td>
                            <td><?php echo $teacher->dob; ?></td>
                            <td><?php echo @$teacher->info->phone_number; ?></td>
                            <td><?php echo $teacher->created_at->format('d/m/Y h:i A'); ?></td>
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
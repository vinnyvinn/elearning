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
                        <th><b style="font-size: 26px;font-weight: 900">Teachers List </b> </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                if($teachers && count($teachers) > 0) {
                    ?>
                    <div class="table-responsive pt-2">
                        <table class="table" id="teachers-datatable">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Teacher ID</th>
                                <th>Phone</th>
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
                                    <td class="table-user">
                                        <?php echo $teacher->profile->name; ?>
                                    </td>
                                    <td><?php echo $teacher->teacher_number; ?></td>
                                    <td><?php echo $teacher->profile->phone; ?></td>
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
                            No teachers were found in the system
                        </div>
                    </div>
                    <?php
                }
                ?>
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
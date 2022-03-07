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



</body>
</html>

<script>
 window.print();
  setTimeout(() => {
      window.close();
  },3000)


</script>
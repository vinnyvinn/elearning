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
                        <th><b style="font-size: 26px;font-weight: 900">Subject List </b> </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php

            use App\Models\Subjects;

            $subjects = (new Subjects())->findAll();
            if ($subjects && count($subjects) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table" id="subjects-datatable">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($subjects as $subject) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $subject->name; ?></td>
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
                        No subjects found
                    </div>
                </div>
                <?php
            }
            ?>
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
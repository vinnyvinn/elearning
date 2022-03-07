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
                        <th><b style="font-size: 26px;font-weight: 900">Tel: <?php echo $phones;?></b></th>
                        </tr>
                        <tr>
                        <th><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                        </tr>
                        <tr>
                        <th><b style="font-size: 26px;font-weight: 900"><?php echo ($class && $section) ? (new \App\Models\Classes())->find($class)->name.(new \App\Models\Sections())->find($section)->name : 'ALL STUDENT'?></b> </th>
                        </tr>
                        <tr>
                        <th><b style="font-size: 26px;font-weight: 900">Students List </b> </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
        if ($students && count($students) > 0) {?>
            <div class="table-responsive pt-2">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Adm #</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Admission Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($students as $student) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td class="table-user">
                            <?php echo $student->profile->name;?>
                            </td>
                            <td><?php echo $student->admission_number; ?></td>
                            <td><?php echo isset($student->class->name) ? $student->class->name : ''; ?></td>
                            <td><?php echo isset($student->section->name)? $student->section->name:''; ?></td>
                            <td><?php echo $student->admission_date ? date('d/m/Y',strtotime($student->admission_date)) : ''; ?></td>
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
                    No students found
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
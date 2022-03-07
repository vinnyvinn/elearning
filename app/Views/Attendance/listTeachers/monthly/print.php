<!DocType html>
<html lang="en">
<head>
   <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap2.min.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css" media="all">
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin-top: 1mm !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-bottom: 0 !important;
                size: 400mm 320mm;
                height: 100%;

            }
            body {
                margin-top: 1mm !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-bottom: 0 !important;
            }
        }
    </style>
    <style>
        .red{
            background: red;
        }
        .blue{
            background: blue;
        }
        .yellow{
            background: yellow;
        }
        .text-green {
            color: #2dce89 !important;
        }
    </style>
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
                            <th><b style="font-size: 26px;font-weight: 900"><?php echo get_option('website_location');?></b></th>
                        </tr>
                        <tr>
                            <th><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                        </tr>
                        <tr>
                            <th><b style="font-size: 26px;font-weight: 900">Teachers Monthly Attendance Counter</b> </th>
                        </tr>
                        <tr>
                            <th><b style="font-size: 18px;font-weight: 900"><?php echo getMonthName($month)?></b> </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
            if ($attendance && count($attendance) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="attend-table">
                        <thead class="thead-light">
                        <tr>
                            <th>Date </th>
                            <th>No. of Present Teachers</th>
                            <th>No. of Absent Teachers</th>
                            <th>No. of Permission Teachers</th>
                            <th>No. of Sick Teachers</th>
                            <th>No. of Late Teachers</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($attendance as $item) {
                            ?>
                            <tr>
                                <td><?php echo $item['date']; ?></td>
                                <td><?php echo $item['teachers']-$item['absent']-$item['permission']-$item['sick']-$item['late'] .'/'.$item['teachers'];?></td>
                                <td><?php echo $item['absent'] .'/'.$item['teachers']; ?></td>
                                <td><?php echo $item['permission'] .'/'.$item['teachers']; ?></td>
                                <td><?php echo $item['sick'] .'/'.$item['teachers']; ?></td>
                                <td><?php echo $item['late'] .'/'.$item['teachers']; ?></td>
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
                        No attendance found for this class
                    </div>
                </div>
                <?php
            }?>

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
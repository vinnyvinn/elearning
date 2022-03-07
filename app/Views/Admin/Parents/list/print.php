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
                            <th><b style="font-size: 26px;font-weight: 900">Parents List </b> </th>
                        </tr>
                        <tr>
                        <th><b style="font-size: 26px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
            if($parents && count($parents) > 0) {
                ?>
                <div class="table-responsive pt-2">
                    <table class="table" id="parents-table">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th># of Students</th>
                            <th>Phone</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($parents as $parent) {
                            $pa = (new \App\Models\Parents())->find($parent->id);
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td class="table-user">
                                    <?php echo $parent->name; ?>
                                </td>
                                <td><?php echo count($pa->students)?></td>
                                <td><?php echo $parent->phone; ?></td>
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
                        No parents were found in the system
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
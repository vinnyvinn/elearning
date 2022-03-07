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
                            <th><b style="font-size: 26px;font-weight: 900">Transport Routes </b> </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
            $routes = (new \App\Models\TransportRoutes())->orderBy('id', 'DESC')->findAll();
            if($routes && count($routes) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table" id="routes-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Driver</th>
                            <th>Phone</th>
                            <th>Vehicle</th>
                            <th>Route</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($routes as $route) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $route->driver_name; ?></td>
                                <td><?php echo $route->driver_phone; ?></td>
                                <td><?php echo $route->licence_plate; ?></td>
                                <td><?php echo $route->route; ?></td>
                                <td><?php echo fee_currency($route->price); ?></td>
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
                <div class="alert alert-routes">
                    No transport routes have been added
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
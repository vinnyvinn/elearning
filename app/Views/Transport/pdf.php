<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>Transport Routes</title>
</head>
<body id="download">
<div id="pannation-project">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body" style="margin-left: 30%">
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
                                <th><b style="font-size: 26px;font-weight: 900">Transport Routes</b> </th>
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
</div>
</body>

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = 'Transport Routes';

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



<?php
$framework = get_option('timetable_framework_'.$section->class->id, get_option('timetable_framework', FALSE));
$builder = new \App\Models\Timetable();
$class = $section->class->id;
?>
<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/nucleo/css/nucleo.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <title>Regular Class Schedule</title>
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
                                <th><b style="font-size: 30px;font-weight: 900"><?php echo get_option('id_school_name')?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 30px;font-weight: 900"><?php echo get_option('website_location');?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 30px;font-weight: 900"><?php echo getSession()->name;?></b></th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 30px;font-weight: 900">Regular Class Schedule </b> </th>
                            </tr>
                            <tr>
                                <th><b style="font-size: 30px;font-weight: 900"><?php echo $section->class->name.' '.$section->name;?> </b> </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                if($framework) {
                    $framework = json_decode($framework, TRUE);
                    ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                            <tr>
                                <th style="font-size: 24px">Day</th>
                                <?php
                                foreach($framework as $time) {
                                    ?>
                                    <th style="font-size: 22px"><?php echo '<b>'.$time['time'].'</b>'; ?></th>
                                    <?php
                                }
                                ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $days = json_decode(get_option('school_days', json_encode(['Mon', 'Tue', 'Wed', 'Thur', 'Fri'])), true);
                            foreach ($days as $day) {
                                ?>
                                <tr>
                                    <th style="font-size: 24px"><?php echo '<b>'.$day.'</b>'; ?></th>
                                    <?php
                                    foreach ($framework as $time) {
                                        ?>
                                        <td style="font-size: 24px">
                                            <?php
                                            if($time['break']) {
                                                echo '<b>'.$time['label'].'</b>';
                                            } else {
                                                $sub = $builder->where(['class' => $class, 'section' => $section->id, 'day' => $day, 'time' => $time['time']])->first();
                                                if($sub && $sub->subject) {
                                                    echo $sub->subject->name;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
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
                        <div class="alert alert-warning">
                            Timetable is not set up
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

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = 'Regular Class Schedule';

    var element = document.getElementById('pannation-project');
    var opt = {
        margin:       0,
        filename:     name+'.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { dpi: 800, letterRendering: true},
        jsPDF:        { unit: 'in', format: 'A0', orientation: 'portrait' }
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



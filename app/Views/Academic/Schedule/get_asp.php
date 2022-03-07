<?php
$framework = [
    [
        'time'  => "08:00 - 09:00",
        'break' => false,
        'label' => ""
    ], [
        'time'  => "09:00 - 10:00",
        'break' => true,
        'label' => "Refreshment"
    ], [
        'time'  => "09:00 - 10:00",
        'break' => false,
        'label' => ""
    ]
];
//$framework = json_encode($framework);
$framework = get_option('asp_timetable_framework', FALSE);
$builder = new \App\Models\AspSchedules();
$class = $section->class->id;


if(isset($print) && $print === true) {
    ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap">
    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/nucleo/css/nucleo.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css">
    <script>
        window.print();
       setTimeout(()=>{
        window.close();
       },2000)
    </script>
    <?php
}
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">After School Program Timetable
            <?php
            if(!isset($print) || $print !== true) {
                ?>
                <a class="pull-right float-right btn btn-sm btn-success m-1" href="<?php echo site_url(route_to('admin.class.sections.create_asp_schedule', $section->id)); ?>"> <i class="fa fa-plus-square"></i> Create/Update Timetable</a>
                <a class="pull-right float-right btn btn-sm btn-info m-1" target="_blank" href="<?php echo site_url(route_to('admin.academic.print_asp_schedule', $section->id)); ?>"> <i class="fa fa-print"></i> Print</a>
                <?php
            }
            ?>
        </h3>
    </div>
    <?php
    if($framework) {
        $framework = json_decode($framework, TRUE);
        ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                <tr>
                    <th>Day</th>
                    <?php
                    foreach($framework as $time) {
                        ?>
                        <th><?php echo '<b>'.$time['time'].'</b>'; ?></th>
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
                        <th><?php echo '<b>'.$day.'</b>'; ?></th>
                        <?php
                        foreach ($framework as $time) {
                            ?>
                            <td>
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
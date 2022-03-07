<?php
/***
 * Created by Bennito254
 *
 * Github: https://github.com/bennito254
 * E-Mail: bennito254@gmail.com
 */

$framework = get_option('timetable_framework_'.$section->class->id, get_option('timetable_framework', FALSE));
$builder = new \App\Models\Timetable();
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
        window.close();
    </script>
    <?php
}
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Regular Timetable
            <?php
            if(!isset($print) || $print !== true) {
                ?>
                <a class="pull-right float-right btn btn-sm btn-info m-1" target="_blank" href="<?php echo site_url(route_to('admin.academic.print_regular_schedule', $section->id)); ?>"> <i class="fa fa-print"></i> Print</a>
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
            <table class="table table-bordered" id="regular">
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

<script>
 $(function (){
     getView();
 })

 $('.walla').on('click',function (){
     getView();
 })

 function getView(){
     const r = document.getElementById('regular');
     r.scrollIntoView();
 }

</script>

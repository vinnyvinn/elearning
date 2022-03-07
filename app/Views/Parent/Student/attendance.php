<?php
$start = $year . '-' . $month . '-01';
$original = $start;
$end = $year . '-' . $month . '-' . date('t', strtotime($start));

$dates = [];
while (strtotime($start) <= strtotime($end)) {
    $day_num = date('d', strtotime($start));

    $start = date("Y-m-d", strtotime("+1 day", strtotime($start)));

    $dates[] = $year . '-' . $month . '-' . $day_num;
}
?>
    <div class="row justify-content-center align-content-center">
        <div class="col-md-6">
            <div class="card bg-default text-white">
                <div class="card-body">
                    <div class="row">

                       <div class="col-md-4">
                           <h3 class="text-white"><b>ቀሪ - </b> <span class="text-danger" style="font-size: 20px">x</span></h3>
                           <h3 class="text-white"><b>የመጣ - </b> <i class="fa fa-check-circle  text-green" style="font-size: 20px"></i></span></h3>
                           <h3 class="text-white"><b>ፈቃድ - </b><span class="text-danger">P</span></h3>
                       </div>
                       <div class="col-md-4">
                           <h3 class="text-white"><b>እርፋጅ - </b><span class="text-danger">L</span></h3>
                           <h3 class="text-white"><b>የታመመ - </b><span class="text-danger">S</span></h3>
                       </div>
                        <div class="col-md-4">
                            <h3 class="text-center text-white"><b>በዓላት / ዝግጅት - &nbsp;  </b><span class="text-blue" style="border-left: 15px solid;border-right: 15px solid;"></span></h3>
                            <h3 class="text-center text-white"><b>ቅዳሜ / እሁድ - &nbsp;  </b><span class="text-yellow" style="border-left: 15px solid;border-right: 15px solid;"></span></h3>
                            <h3 class="text-center text-white"><b>ፈተና - &nbsp;  </b><span class="text-danger" style="border-left: 15px solid;border-right: 15px solid;"></span></h3>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="text-center text-white"><b><?php echo date('F, Y', strtotime($original)); ?></b></h3>
                    <h5 class="text-center text-white"><?php echo $student->profile->name; ?></h5>
                    <h5 class="text-center text-white"><?php echo $student->admission_number; ?></h5>
                    <h5 class="text-center text-white"><?php echo $student->class->name.', '.$student->section->name; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered attend-table" id="attend-table">
            <thead class="thead-light">
            <tr>
                <?php
                foreach ($dates as $date) {
                    ?>
                    <th><?php echo date('d', strtotime($date)); ?></th>
                    <?php
                }
                ?>
                <th>P</th>
                <th>A</th>
                <th>Ps</th>
                <th>S</th>
                <th>L</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            $attendance = new \App\Models\Attendance();
                ?>
                <tr>
                    <?php
                    foreach ($dates as $date) {
                        $att = $attendance->where('timestamp', strtotime($date))->where('student', $student->id)->get()->getRowObject();

                        ?>
                        <td class="events <?php if ($att){if ($att->status == 5 && $att->option_type=='exams'):?>red <?php elseif ($att->status == 5 && $att->option_type=='weekend'):?>yellow <?php elseif ($att->status == 5 && $att->option_type=='events'):?>blue<?php endif;}?>">
                            <?php
                            if($att) {
                                if ($att->status == 1):
                                    echo '<span class="text-green"><i class="fa fa-check-circle"></i></span>';
                                elseif ($att->status == 0):
                                    echo '<span class="text-danger">x</span>';
                                elseif ($att->status == 2):
                                    echo 'P';
                                elseif ($att->status == 3):
                                    echo 'S';
                                elseif ($att->status == 4):
                                    echo 'L';
                                endif;
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td><?php echo studentInfo($student->id,$month)['present'];?></td>
                    <td><?php echo studentInfo($student->id,$month)['absent'];?></td>
                    <td><?php echo studentInfo($student->id,$month)['permission'];?></td>
                    <td><?php echo studentInfo($student->id,$month)['sick'];?></td>
                    <td><?php echo studentInfo($student->id,$month)['late'];?></td>
                </tr>
            </tbody>
        </table>
    </div>

<script>
   $(function (){
        var att = document.getElementById('attend-table');
        att.scrollIntoView();
   })
  $('.walla').on('click',function (){
      var att = document.getElementById('attend-table');
      att.scrollIntoView();
  })
</script>
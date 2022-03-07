<?php
$classes = @getSession()->classes->findAll();

$students = @getSession()->students;
//$subjects = $classes[0]->subjects;

   $students_arr = array();
   $subjects_passed = array();

    foreach ($students as $student) {
        $subjects = $student->class->subjects;
        if (isset($student->class->id)) {
            $resultModel = new \App\Libraries\YearlyResults($student->id, active_session());
            $result = $resultModel->getExamResults($semester);
          //$result = $resultModel->getSemesterTotalResultsPerSubjectClass($semester,$student->class->id);
           if (!isset($students_arr[$student->id])){
               $students_arr[$student->id] = (is_numeric($result) && $result > 0) ? ($result > 0 && count($subjects) > 0 ? number_format($result/count($subjects),2) : 0) : 0;
           }

        }
    }

    $boys_49_and_below = array();
    $girls_49_and_below = array();
    $boys_50_to_74 = array();
    $girls_50_to_74 = array();
    $boys_75_to_100 = array();
    $girls_75_to_100 = array();

    $boys_counter_below_50 = array();
    $girls_counter_below_50 = array();

    $boys_promoted = array();
    $girls_promoted = array();
    $boys_detained = array();
    $girls_detained = array();

    $boys_exams_seated = array();
    $girls_exams_seated = array();

    foreach ($students_arr as $key => $val){
       $student = (new \App\Models\Students())->find($key);
        $exams_result_count = count((new \App\Models\ExamResults())->where('student',$student->id)->where('not_seated_for_exam',1)->findAll());
        $no_of_exams = get_option('no_of_exams')?:1;

        //Male block
       if ($student->profile->gender =='Male') {
           if ($exams_result_count < $no_of_exams){
           if (!isset($boys_exams_seated[$student->class->id])){
               $boys_exams_seated[$student->class->id] = 1;
           }else {
               $boys_exams_seated[$student->class->id] += 1;
           }
           }
           if ($val <= 49) {
               if (!isset($boys_49_and_below[$student->class->id])) {
                   $boys_49_and_below[$student->class->id] = 1;
               } else {
                   $boys_49_and_below[$student->class->id] += 1;
                   if ($boys_49_and_below[$student->class->id] >= 3){
                       if (!isset($boys_counter_below_50[$student->class->id])){
                           $boys_counter_below_50[$student->class->id] = 1;
                       }else{
                           $boys_counter_below_50[$student->class->id] += 1;
                       }
                   }
               }
           }
           elseif($val >= 50 && $val <= 74){
               if (!isset($boys_50_to_74[$student->class->id])) {
                   $boys_50_to_74[$student->class->id] = 1;
               } else {
                   $boys_50_to_74[$student->class->id] += 1;
               }
           }

           elseif($val >= 75 && $val <= 100){
               if (!isset($boys_75_to_100[$student->class->id])) {
                   $boys_75_to_100[$student->class->id] = 1;
               } else {
                   $boys_75_to_100[$student->class->id] += 1;
               }
           }


           if ($val >= $student->class->pass_mark){
             if (!isset($boys_promoted[$student->class->id])){
                 $boys_promoted[$student->class->id] = 1;
             }else {
                 $boys_promoted[$student->class->id] += 1;
             }
           }else{
               if (!isset($boys_detained[$student->class->id])){
                   $boys_detained[$student->class->id] = 1;
               }else {
                   $boys_detained[$student->class->id] += 1;
               }
           }

       }
       //female block
       else {
           if ($val <= 49){
           if (!isset($girls_49_and_below[$student->class->id])){
               $girls_49_and_below[$student->class->id] = 1;
           }else {
               $girls_49_and_below[$student->class->id] += 1;
           }
               if ($girls_49_and_below[$student->class->id] >= 3){
                   if (!isset($girls_counter_below_50[$student->class->id])){
                       $girls_counter_below_50[$student->class->id] = 1;
                   }else {
                       $girls_counter_below_50[$student->class->id] += 1;
                   }
               }
       }
           elseif ($val >= 50 && $val <= 74){
               if (!isset($girls_50_to_74[$student->class->id])) {
                   $girls_50_to_74[$student->class->id] = 1;
               } else {
                   $girls_50_to_74[$student->class->id] += 1;
               }
           }
           elseif($val >= 75 && $val <= 100){
               if (!isset($girls_75_to_100[$student->class->id])) {
                   $girls_75_to_100[$student->class->id] = 1;
               } else {
                   $girls_75_to_100[$student->class->id] += 1;
               }
           }
           if ($val >= $student->class->pass_mark){
               if (!isset($girls_promoted[$student->class->id])){
                   $girls_promoted[$student->class->id] = 1;
               }else {
                   $girls_promoted[$student->class->id] += 1;
               }
           }else{
               if (!isset($girls_detained[$student->class->id])){
                   $girls_detained[$student->class->id] = 1;
               }else {
                   $girls_detained[$student->class->id] += 1;
               }
           }
           if ($exams_result_count < $no_of_exams){
               if (!isset($girls_exams_seated[$student->class->id])){
                   $girls_exams_seated[$student->class->id] = 1;
               }else {
                   $girls_exams_seated[$student->class->id] += 1;
               }
           }
      }
    }
?>

<style>
  table,th,td{
   border: 1px solid black !important;
  }
</style>
<div class="table-responsive">
    <table class="table">
        <tr>
          <th rowspan="5" style="vertical-align: middle">የተመዘገቡ</th>
        </tr>
        <tr>
        <th>ፆታ</th>
         <?php foreach ($classes as $class):?>
         <th><?php echo $class->name;?></th>
        <?php endforeach;?>
        </tr>
       <tr>
         <th>ወ</th>
           <?php foreach ($classes as $class):?>
            <th><?php echo $class->boys;?></th>
           <?php endforeach;?>
       </tr>
        <tr>
           <th>ሴ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->girls;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ድ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->girls+$class->boys;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th rowspan="5" style="vertical-align: middle">ያቋረጡ</th>
        </tr>
        <tr>
            <th>ፆታ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->name;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ወ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo boysDeparture($class->id,$semester);?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ሴ</th>

            <?php foreach ($classes as $class):?>
                <th><?php echo girlsDeparture($class->id,$semester);?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ድ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo girlsDeparture($class->id,$semester)+boysDeparture($class->id,$semester);?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th rowspan="5" style="vertical-align: middle">ለፈተና የተቀመጡ</th>
        </tr>
        <tr>
            <th>ፆታ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->name;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ወ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo isset($boys_exams_seated[$class->id]) ? $boys_exams_seated[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ሴ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo isset($girls_exams_seated[$class->id]) ? $girls_exams_seated[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ድ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo (isset( $boys_exams_seated[$class->id]) ?  $boys_exams_seated[$class->id] : 0) + (isset($girls_exams_seated[$class->id]) ? $girls_exams_seated[$class->id] : 0);?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th rowspan="5" style="vertical-align: middle">አማካኝ 49 እና በታች ያገኙ</th>
        </tr>
        <tr>
            <th>ፆታ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->name;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ወ</th>

            <?php foreach ($classes as $class):?>
                <th><?php  echo isset($boys_49_and_below[$class->id]) ? $boys_49_and_below[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ሴ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo isset($girls_49_and_below[$class->id]) ? $girls_49_and_below[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ድ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo (isset($boys_49_and_below[$class->id]) ? $boys_49_and_below[$class->id] : 0) + (isset($girls_49_and_below[$class->id]) ? $girls_49_and_below[$class->id] : 0);?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th rowspan="5" style="vertical-align: middle">አማካኝ 50 - 74 ያገኙ</th>
        </tr>
        <tr>
            <th>ፆታ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->name;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ወ</th>

            <?php foreach ($classes as $class):?>
                <th><?php  echo isset($boys_50_to_74[$class->id]) ? $boys_50_to_74[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ሴ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo isset($girls_50_to_74[$class->id]) ? $girls_50_to_74[$class->id] : 0; ?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ድ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo (isset($boys_50_to_74[$class->id]) ? $boys_50_to_74[$class->id] : 0) + (isset($girls_50_to_74[$class->id]) ? $girls_50_to_74[$class->id] : 0);?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th rowspan="5" style="vertical-align: middle">አማካኝ 75 - 100 ያገኙ</th>
        </tr>
        <tr>
            <th>ፆታ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->name;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ወ</th>

            <?php foreach ($classes as $class):?>
                <th><?php  echo isset($boys_75_to_100[$class->id]) ? $boys_75_to_100[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ሴ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo isset($girls_75_to_100[$class->id]) ? $girls_75_to_100[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ድ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo (isset($boys_75_to_100[$class->id]) ? $boys_75_to_100[$class->id] : 0) + (isset($girls_75_to_100[$class->id]) ? $girls_75_to_100[$class->id] : 0);?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th rowspan="5" style="vertical-align: middle">በ3 እና በላይ  የትም/አይነት ከ50 በታች ያገኙ</th>
        </tr>
        <tr>
            <th>ፆታ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->name;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ወ</th>

            <?php foreach ($classes as $class):?>
                <th><?php  echo isset($boys_counter_below_50[$class->id]) ? $boys_counter_below_50[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ሴ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo isset($girls_counter_below_50[$class->id]) ? $girls_counter_below_50[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ድ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo (isset($boys_counter_below_50[$class->id]) ? $boys_counter_below_50[$class->id] : 0) + (isset($girls_counter_below_50[$class->id]) ? $girls_counter_below_50[$class->id] : 0);?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th rowspan="5" style="vertical-align: middle">ያለፉ</th>
        </tr>
        <tr>
            <th>ፆታ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->name;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ወ</th>

            <?php foreach ($classes as $class):?>
                <th><?php  echo isset($boys_promoted[$class->id]) ? $boys_promoted[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ሴ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo isset($girls_promoted[$class->id]) ? $girls_promoted[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ድ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo (isset($boys_promoted[$class->id]) ? $boys_promoted[$class->id] : 0) + (isset($girls_promoted[$class->id]) ? $girls_promoted[$class->id] : 0);?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th rowspan="5" style="vertical-align: middle">ያላለፉ</th>
        </tr>
        <tr>
            <th>ፆታ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo $class->name;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ወ</th>

            <?php foreach ($classes as $class):?>
                <th><?php  echo isset($boys_detained[$class->id]) ? $boys_detained[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ሴ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo isset($girls_detained[$class->id]) ? $girls_detained[$class->id] : 0;?></th>
            <?php endforeach;?>
        </tr>
        <tr>
            <th>ድ</th>
            <?php foreach ($classes as $class):?>
                <th><?php echo (isset($boys_detained[$class->id]) ? $boys_detained[$class->id] : 0) + (isset($girls_detained[$class->id]) ? $girls_detained[$class->id] : 0);?></th>
            <?php endforeach;?>
        </tr>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('.datatable').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy'
                },
                {
                    extend: 'excel',
                },
                {
                    extend: 'pdf',
                },
                {
                    extend: 'print',
                },
            ],
            "aoColumnDefs": [
                { "sType": "numeric", "aTargets": [ 0, -1 ] }
            ]
        });
    })
</script>
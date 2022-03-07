<!DocType html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap2.min.css'); ?>"/>
<style>

</style>
</head>
<body>

<?php
$std_id = $student->id;

$directors = (new \App\Models\Teachers())->where('is_director',1)->where('session',active_session())->findAll();
$dir = '';
foreach ($directors as $director){
    if ($director->director_classes) {
        if (in_array($student->class->id, json_decode($director->director_classes))) {
        $dir = $director;
    }
    }
}
?>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div>
        <div class="row" style="margin-left: 1%">
            <div class="col-md-8">
                <table>
                    <tr>
                        <th style="text-transform: uppercase"><?php echo $student->parent->usermeta('subcity')?></th>
                    </tr>
                    <tr>
                      <th style="text-transform: uppercase"><?php echo get_option('id_school_name');?></th>
                    </tr>
                    <tr>
                    <th>STUDENT TRANSCRIPT</th>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
              <table>
                  <tr>
                      <td style="text-align: right">  <img src="<?php echo $student->profile->avatar;?>" alt="" width="150" height="150"></td>
                  </tr>
              </table>
            </div>
            <table class="table">
                <tr>
                    <th style="border-top: none">Tel.No <span style="text-decoration: underline"><?php echo $student->parent->usermeta('mobile_phone_work');?></span></th>
                    <th style="border-top: none">P.O.Box <span style="text-decoration: underline">56</span></th>
                </tr>
            </table>

            <table class="table">
                <tr>
                 <th style="padding-top: 0;padding-bottom:0;border-top: none">NAME OF THE STUDENT <span style="text-decoration: underline"><?php echo $student->profile->name;?></span></th>
                    <th style="padding-top: 0;padding-bottom: 0;border-top: none">SEX <span style="text-decoration: underline"><?php echo $student->profile->gender =='Female' ? 'F' : 'M';?></span></th>
                 <th style="padding-top: 0;padding-bottom: 0;border-top: none">MOBILE NO <span style="text-decoration: underline"><?php echo $student->parent->usermeta('mobile_phone_number');?></span></th>
                </tr>
            </table>
            <table class="table">
                <tr>
                    <th style="border-top: none;padding-top: 0;padding-bottom: 0">DATE AND ADMISSION: <span style="text-decoration: underline"><?php echo date('Y',strtotime($student->admission_date));?></span> E.C</th>
                    <th style="border-top: none;padding-top: 0;padding-bottom: 0">DATE OF LEAVING: <span style="text-decoration: underline"><?php  $std = (new \App\Models\Departure())->where('student',$student->id)->get()->getRow();echo date('Y',strtotime($std->created_at));?></span> E.C</th>
                </tr>
            </table>
        </div>

        <div class="card-body">
            <table class="table table-bordered" width="100%" border="1px">
            <thead>
             <tr>
             <th rowspan="3" style="text-align: center;vertical-align: middle !important;">SUBJECT</th>
             <th colspan="3" style="text-align: center"><?php echo $student->class->name;?>
                 <br>
             Year: <span style="text-decoration: underline"><?php echo date('Y',strtotime($student->created_at));?></span>
             </th>
             <th rowspan="3" style="text-align: center;vertical-align: middle !important;">REMARK</th>
             </tr>
             <tr>
             <th colspan="3"  style="text-align: center">SEMESTER</th>
             </tr>
             <tr>
                 <th>I</th>
                 <th>II</th>
                 <th>AVE.</th>
             </tr>
            </thead>
              <tbody>
              <?php
              $session = (new \App\Models\Sessions())->find($student->session->id);
              $semesters = $session->semesters;
              $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
              $total_marks = [];
              $count = 0;
              $n = 0;
              $data_arr = [];
              $data_arr_sem2 = [];
              $data_arr_opt = [];
              $data_arr_opt_sem2 = [];
              $subjects = $student->class->subjects;
              $sem1_total = 0;
              $sem2_total = 0;
              foreach ($subjects as $subj) {
                      $res = $resultsModel->getSemesterTotalResultsPerSubject($semesters[0]->id, $subj->id,$student->section->id);
                      $res2 = $resultsModel->getSemesterTotalResultsPerSubject($semesters[1]->id, $subj->id,$student->section->id);


                      if($res && !empty($res) && $subj->optional == 0) {
                          $sem1_total +=$res;
                          if(!isset($data_arr[$subj->id])){
                              $data_arr[$subj->id] = $res;
                          }else {
                              $data_arr[$subj->id] += $res;
                          }
                      }else if ($res && !empty($res) && $subj->optional == 1){
                          if(!isset($data_arr_opt[$subj->id])){
                              $data_arr_opt[$subj->id] = $res;
                          }else {
                              $data_arr_opt[$subj->id] += $res;
                          }
                      }

                  if($res2 && !empty($res2) && $subj->optional == 0) {
                      $sem2_total += $res2;
                      if(!isset($data_arr_sem2[$subj->id])){
                          $data_arr_sem2[$subj->id] = $res2;
                      }else {
                          $data_arr_sem2[$subj->id] += $res2;
                      }
                  }else if ($res2 && !empty($res2) && $subj->optional == 1){
                      if(!isset($data_arr_opt_sem2[$subj->id])){
                          $data_arr_opt_sem2[$subj->id] = $res2;
                      }else {
                          $data_arr_opt_sem2[$subj->id] += $res2;
                      }
                  }

              }
             //var_dump($subjects);
              foreach ($subjects as $subject) {
              if ($subject->optional == 0)
                  $count++;
                $n++;
              ?>
              <tr>
             <td><?php echo $subject->name; ?></td>
             <td>
                 <?php if ($subject->optional==0):?>
                 <?php echo $data_arr[$subject->id];
                 else:
                     echo $data_arr_opt[$subject->id];
                 endif;
                 ?>
             </td>
             <td><?php
                 if ($subject->optional==0):
                 echo $data_arr_sem2[$subject->id];
                 else:
                 echo $data_arr_opt_sem2[$subject->id];
                 endif;
                  ?></td>
                  <td>
                      <?php
                      echo  ($data_arr[$subject->id]+$data_arr_sem2[$subject->id])/2
                      ?>
                  </td>
                  <td></td>
              </tr>
              <?php }?>
              <tr>
                  <th>TOTAL </th>
                  <td><?php echo number_format($sem1_total,2)?></td>
                  <td><?php echo number_format($sem2_total,2)?></td>
                  <td><?php echo number_format(($sem1_total+$sem2_total)/2,2)?></td>
                  <td></td>
              </tr>
              <tr>
                  <th>AVERAGE</th>
                  <td><?php echo number_format($sem1_total/$count,2);?></td>
                  <td><?php echo number_format($sem2_total/$count,2);?></td>
                  <td><?php echo (number_format(($sem1_total/$count+$sem2_total/$count)/2,2));?></td>
                  <td></td>
              </tr>
              <tr>
                  <?php

                $datasem1_arr_ =  array();
                $datasem2_arr_ =  array();
                foreach ($student->section->students as $student) {
                    $total_marks = 0;
                    $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
                    $subjects = $student->class->subjects;

                    foreach ($subjects as $subj) {
                        $res = $resultsModel->getSemesterTotalResultsPerSubject($semesters[0]->id, $subj->id);
                        if(!isset($datasem1_arr_[$student->id])){
                            $datasem1_arr_[$student->id] = ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                        }else {
                            $datasem1_arr_[$student->id] += ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                        }
                    }
                }

                  foreach ($student->section->students as $student) {
                      $total_marks = 0;
                      $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
                      $subjects = $student->class->subjects;

                      foreach ($subjects as $subj) {
                          $res = $resultsModel->getSemesterTotalResultsPerSubject($semesters[1]->id, $subj->id,$student->section->id);
                          if(!isset($datasem2_arr_[$student->id])){
                              $datasem2_arr_[$student->id] = ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                          }else {$datasem2_arr_[$student->id] += ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                          }

                      }
                  }

                  $ranking = array_rank($datasem1_arr_);
                  $ranking2 = array_rank($datasem2_arr_);
                ?>
                 <th>RANK</th>
                  <td><?php echo $ranking[$std_id];?></td>
                  <td><?php echo $ranking2[$std_id];?></td>
                  <td>
                      <?php
                      $data_arr_ =  array();
                      foreach ($student->section->students as $student) {
                          $total_marks = 0;
                          $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
                          $subjects = $student->class->subjects;

                          foreach ($subjects as $subj) {
                              foreach ($semesters as $item) {
                                  $res = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subj->id,$student->section->id);
                                  if(!isset($data_arr_[$student->id])){
                                      $data_arr_[$student->id] = ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                  }else {
                                      $data_arr_[$student->id] += ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                  }
                              }
                          }
                      }
                      $ranks = array_rank($data_arr_);
                      echo $ranks[$std_id];
                      ?>
                  </td>
                  <td></td>
              </tr>
              <tr>
                  <th>Conduct</th>
                  <td>A</td>
                  <td>A</td>
                  <td>A</td>
                  <td></td>
              </tr>
              </tbody>
            </table>
            <table>
            <tr>
                <td>Remarks _______________________________________________________________________________________</td>
            </tr>
                <tr>
                    <th><b>Note: </b>Erasures, alteration,deletion or Absence of seal invalidate this Transcript.</th>
                </tr>
            </table>
            <table>
                <tr>
                    <th>________________________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>________________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>
                        <?php if (!empty($dir)):?>
                            <div style="padding-top: 10%">Sign: <img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" width="80px" height="80px"></div>
                        <?php endif;?>
                    </th>
                </tr>
                <tr>
                    <td>Record officers's Name & sign</td>
                    <td style="text-align: center">Date of issue</td>
                    <td style="text-align: center">Director's sign</td>
                </tr>
            </table>
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
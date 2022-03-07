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
$limit_sessions = get_option('no_of_years')?get_option('no_of_years') : 1;

$student_sessions = (new \App\Models\Students())->where('admission_number',$student->admission_number)->orderBy('created_at','DESC')->limit($limit_sessions)->groupBy('session')->get()->getResultObject();
//echo '<pre>';
//var_dump($student_sessions);
//exit();
$std_ids = array();
$session_ids = array();
$std_conducts = array();
$subjects = array();
$student_subjects = array();
$mark_subjects = array();
foreach ($student_sessions as $st){
    array_push($std_ids,$st->id);
    array_push($session_ids,$st->session);
    $stud = (new \App\Models\Students())->find($std_id);
    array_push($subjects,$stud->class->subjects);
    foreach ($stud->class->subjects as $sub){
        if (!isset($mark_subjects[$sub->id])){
                $mark_subjects[$sub->id] = true;
                array_push($student_subjects,$sub);
        }
    }
}

$all_sessions = (new \App\Models\Sessions())->whereIn('id',$session_ids)->findAll();
$student_sessions = (new \App\Models\Students())->whereIn('id',$std_ids)->findAll();

$teacher = (new \App\Models\Teachers())->find($student->section->advisor->id);
foreach ($student_sessions as $sess){
    $conduct = (new \App\Models\YearlyStudentEvaluation())->where('student',$sess->id)->where('class',$sess->class->id)->where('section',$sess->section->id)->where('session',$sess->session->id)->get()->getLastRow();
   if (!isset($std_conducts[$sess->id])){
        $std_conducts[$sess->id] = $conduct;
    }
}
?>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div>
        <div class="row" style="margin-left: 1%">
            <div class="col-md-3">

                <img src="<?php echo base_url()?>/uploads/files/<?php echo get_option('website_logo')?>" width="200">
            </div>
            <div class="col-md-9">
              <table width="100%">
                  <tr>
                     <th style="text-transform: uppercase;font-size: 30px;padding-left: 4%"><?php echo get_option('id_school_name')?></th>
                  </tr>
                  <tr>

                      <th style="border-top: none;font-size: 26px"><span>
                           <?php
                           $phones = get_option('website_phone') ? json_decode(get_option('website_phone')) : '';

                           if ($phones)
                               $phones = implode(' |&nbsp;&nbsp;',$phones);
                           echo $phones;
                           ?>
                          </span></th>

                  </tr>
                  <tr>
                      <th style="border-top: none;font-size: 26px"><span><?php echo get_option('website_location')?></span></th>
                  </tr>
              </table>
            </div>
        </div>


            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="col-md-4">
                        <img src="<?php echo $student->pic;?>" alt="" class="avatar" height="180">
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tr>
                                <th style="border-top: none;font-size: 22px;width: 66%">NAME OF THE STUDENT: <span style="text-decoration: underline;font-size: 22px"><?php echo $student->profile->name;?></span></th>
                                <th style="border-top: none;font-size: 22px">SEX: <span style="text-decoration: underline"><?php echo $student->profile->gender =='Female' ? 'F' : 'M';?></span></th>
                            </tr>
                        </table>
                        <table class="table">
                            <tr>
                                <th style="border-top:none;font-size: 22px">DATE OF ADMISSION: <span style="text-decoration: underline"><?php echo date('d/m/Y',strtotime($student->admission_date));?></span></th>
                                <th style="border-top: none;font-size: 22px">Tel: <span style="text-decoration: underline"><?php echo $student->parent->usermeta('mobile_phone_number');?></span></th>
                            </tr>
                            <tr>
                                <th style="border-top: none;font-size: 22px">DATE OF LEAVING: <?php if (get_option('transcript_date_of_leaving'.$student->id)):?><span style="text-decoration: underline"><?php echo get_option('transcript_date_of_leaving'.$student->id)?></span><?php else:?>______________________________<?php endif;?></th>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered" width="100%" border="1px">
            <thead>
             <tr>
             <th rowspan="3" style="text-align: center;vertical-align: middle !important;">SUBJECT</th>
               <?php foreach ($student_sessions as $std):
               ?>
             <th colspan="3">
                 GRADE: <?php echo $std->class->name;?> &nbsp;&nbsp;SECTION: <?php echo $std->section->name;?>

                 <br>
             YEAR: <span style="text-decoration: underline"><?php echo date('Y',strtotime($std->created_at));?></span>
             </th>
               <?php endforeach;?>
             </tr>
             <tr>
             <?php foreach ($student_sessions as $std):
             ?>
             <th colspan="3"  style="text-align: center">SEMESTER</th>
             <?php endforeach;?>
             </tr>
             <tr>
                 <?php foreach ($student_sessions as $std):
                 ?>
                 <th>I</th>
                 <th>II</th>
                 <th>AVE.</th>
                 <?php endforeach;?>
             </tr>
            </thead>
              <tbody>
              <?php
              $total_marks = [];
              $count = 0;
              $n = 0;
              $data_arr = array();
              $data_arr_sem2 = [];
              $data_arr_opt = [];
              $data_arr_opt_sem2 = [];
              $sem1_total = array();
              $sem2_total = array();
              foreach ($student_sessions as  $sess):
                  $session = (new \App\Models\Sessions())->find($sess->session->id);
                  $semesters = $session->semesters;
                  $resultsModel = new \App\Libraries\YearlyResults($sess->id, $session->id);
              foreach ($student_subjects as $subj) {

                      $res = $resultsModel->getSemesterTotalResultsPerSubject($semesters[0]->id, $subj->id,$sess->section->id);
                      $res2 = $resultsModel->getSemesterTotalResultsPerSubject($semesters[1]->id, $subj->id,$sess->section->id);

                      if($res && !empty($res) && $subj->optional == 0) {
                          if (!isset($sem1_total[$sess->session->id])){
                              $sem1_total[$sess->session->id] = $res;
                          }else {
                              $sem1_total[$sess->session->id] += $res;
                          }
                          if(!isset($data_arr[$session->id.''.$subj->subject])){
                              $data_arr[$session->id.''.$subj->subject] = $res;
                          }else {
                              $data_arr[$session->id.''.$subj->subject] += $res;
                          }
                      }else if ($res && !empty($res) && $subj->optional == 1){
                          if(!isset($data_arr_opt[$session->subject.''.$subj->subject])){
                              $data_arr_opt[$session->subject.''.$subj->subject] = $res;
                          }else {
                              $data_arr_opt[$session->subject.''.$subj->subject] += $res;
                          }
                      }

                  if($res2 && !empty($res2) && $subj->optional == 0) {
                      if (!isset($sem2_total[$sess->session->id])){
                          $sem2_total[$sess->session->id] = $res2;
                      }else {
                          $sem2_total[$sess->session->id] += $res2;
                      }
                      if(!isset($data_arr_sem2[$session->id.''.$subj->subject])){
                          $data_arr_sem2[$session->id.''.$subj->subject] = $res2;
                      }else {
                          $data_arr_sem2[$session->id.''.$subj->subject] += $res2;
                      }
                  }else if ($res2 && !empty($res2) && $subj->optional == 1){
                      if(!isset($data_arr_opt_sem2[$session->id.''.$subj->subject])){
                          $data_arr_opt_sem2[$session->id.''.$subj->subject] = $res2;
                      }else {
                          $data_arr_opt_sem2[$session->id.''.$subj->subject] += $res2;
                      }
                  }

              }
              endforeach;

              foreach ($student_subjects as $subject) {
              if ($subject->optional == 0)
                  $count++;
                 $n++;
              ?>
              <tr>
             <td><?php echo $subject->name; ?></td>

              <?php
              foreach ($student_sessions as $std):?>
                 <td style="font-size: 20px">
                 <?php if ($subject->optional==0):?>
                 <?php echo isset($data_arr[$std->session->id.''.$subject->subject]) ? $data_arr[$std->session->id.''.$subject->subject] :'-';
                 else:
                     echo isset($data_arr_opt[$std->session->id.''.$subject->subject]) ? $data_arr_opt[$std->session->id.''.$subject->subject] :'-';
                 endif;
                 ?>
             </td>
              <td style="font-size: 20px"><?php
                 if ($subject->optional==0):
                 echo isset($data_arr_sem2[$std->session->id.''.$subject->subject]) ? $data_arr_sem2[$std->session->id.''.$subject->subject] :'-';
                 else:
                 echo isset($data_arr_opt_sem2[$std->session->id.''.$subject->subject]) ? $data_arr_opt_sem2[$std->session->id.''.$subject->subject] :'-';
                 endif;
                  ?></td>
                  <td style="font-size: 20px">
                      <?php
                      $res1 = isset($data_arr[$std->session->id.''.$subject->subject]) ? $data_arr[$std->session->id.''.$subject->subject] : 0;
                      $res2 = isset($data_arr_sem2[$std->session->id.''.$subject->subject]) ? $data_arr_sem2[$std->session->id.''.$subject->subject] : 0;
                      echo  ($res1+$res2)/2
                      ?>
                  </td>
                  <?php endforeach;
                  ?>
              </tr>
              <?php }
              ?>
              <tr>
                  <th style="font-size: 20px">TOTAL </th>
                  <?php  foreach ($student_sessions as $std):

                      ?>
                  <td style="font-size: 20px"><?php echo isset($sem1_total[$std->session->id]) ? number_format($sem1_total[$std->session->id],2) :'';

                  ?></td>
                  <td style="font-size: 20px"><?php echo isset($sem2_total[$std->session->id]) ? number_format($sem2_total[$std->session->id],2) :''?></td>
                  <td style="font-size: 20px"><?php
                      $res1 = isset($sem1_total[$std->session->id]) ? $sem1_total[$std->session->id] : 0;
                      $res2 = isset($sem2_total[$std->session->id]) ? $sem2_total[$std->session->id] : 0;
                      echo number_format(($res1+$res2)/2,2)?></td>
                  <?php endforeach;

                  ?>
              </tr>
              <tr>
                  <th style="font-size: 20px">AVERAGE</th>
                  <?php
                  foreach ($student_sessions as $std):?>
                  <td style="font-size: 20px"><?php echo isset($sem1_total[$std->session->id]) ? number_format($sem1_total[$std->session->id]/$std->class->subjectsCount,2):0;?></td>
                  <td style="font-size: 20px"><?php echo isset($sem2_total[$std->session->id]) ? number_format($sem2_total[$std->session->id]/$std->class->subjectsCount,2) : 0;?></td>
                  <td style="font-size: 20px"><?php
                      $res1 = isset($sem1_total[$std->session->id]) ? $sem1_total[$std->session->id]/$std->class->subjectsCount : 0;
                      $res2 = isset($sem2_total[$std->session->id]) ? $sem2_total[$std->session->id]/$std->class->subjectsCount : 0;
                      echo (number_format(($res1+$res2)/2,2));?></td>
                  <?php endforeach;?>
              </tr>
              <tr>
                  <?php

                $datasem1_arr_ =  array();
                $datasem2_arr_ =  array();
                $data_arr_  = array();

              foreach ($student_sessions as $sess):
                  $semesters = (new \App\Models\Sessions())->find($sess->session->id)->semesters;
                foreach ($sess->section->students as $student) {
                    $total_marks = 0;
                    $resultsModel = new \App\Libraries\YearlyResults($student->id, $sess->session->id);
                    $subjects = $student->class->subjects;
                    foreach ($subjects as $subj) {
                        $res = $resultsModel->getSemesterTotalResultsPerSubject($semesters[0]->id, $subj->id);
                        $res2 = $resultsModel->getSemesterTotalResultsPerSubject($semesters[1]->id, $subj->id);
                        if(!isset($datasem1_arr_[$sess->session->id.''.$student->admission_number])){
                            $datasem1_arr_[$sess->session->id.''.$student->admission_number] = ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                        }else {
                            $datasem1_arr_[$sess->session->id.''.$student->admission_number] += ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                        }
                        if(!isset($datasem2_arr_[$sess->session->id.''.$student->admission_number])){
                            $datasem2_arr_[$sess->session->id.''.$student->admission_number] = ($res2 && !empty($res2) && $subj->optional == 0) ?  $res2 : 0;
                        }else {
                            $datasem2_arr_[$sess->session->id.''.$student->admission_number] += ($res2 && !empty($res2) && $subj->optional == 0) ?  $res2 : 0;
                        }
                        if(!isset($data_arr_[$sess->session->id.''.$student->admission_number])){
                            $res1 =($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                            $res2 =($res2 && !empty($res2) && $subj->optional == 0) ?  $res2 : 0;
                            $data_arr_[$sess->session->id.''.$student->admission_number] = $res1 + $res2;
                        }else {
                            $data_arr_[$sess->session->id.''.$student->admission_number] += ($res1+$res2);
                        }

                    }
                }
                endforeach;
                  $ranking = array_rank($datasem1_arr_);
                  $ranking2 = array_rank($datasem2_arr_);
                ?>
                 <th style="font-size: 20px">RANK</th>
                  <?php  foreach ($student_sessions as $std):
                      ?>
                  <td style="font-size: 20px"><?php echo isset($ranking[$std->session->id.''.$std->admission_number]) ? $ranking[$std->session->id.''.$std->admission_number] : '';?></td>
                  <td style="font-size: 20px"><?php echo isset($ranking2[$std->session->id.''.$std->admission_number]) ? $ranking2[$std->session->id.''.$std->admission_number] : '';?></td>
                  <td style="font-size: 20px">
                      <?php
                      $ranks = array_rank($data_arr_);
                      echo isset($ranks[$std->session->id.''.$std->admission_number]) ? $ranks[$std->session->id.''.$std->admission_number] : '';
                      ?>
                  </td>
                  <?php endforeach;?>
              </tr>
              <tr>
                  <th style="font-size: 20px">Conduct</th>
                  <?php  foreach ($student_sessions as $std):?>
                  <td style="font-size: 20px"><?php echo isset($std_conducts[$std->id]->first_sem_evaluation) ? $std_conducts[$std->id]->first_sem_evaluation: '';?></td>
                  <td style="font-size: 20px"><?php echo isset($std_conducts[$std->id]->second_sem_evaluation) ? $std_conducts[$std->id]->second_sem_evaluation : '';?></td>
                  <td></td>
                  <?php endforeach;?>
              </tr>

              </tbody>
            </table>
            <table>
            <tr>
                <th> <span style="font-size: 20px">Remarks</span> <?php if (get_option('transcript_remarks'.$student->id)):?><span style="text-decoration: underline"><?php echo get_option('transcript_remarks'.$student->id)?></span><?php else:?> ______________________________________________________________________________________________________________________________________________________________________________<?php endif;?></th>
            </tr>
                <tr>
                    <th><b style="font-size: 20px;margin-left: 10%">Note: </b><span style="font-size: 20px">Erasures, alteration,deletion or Absence of seal invalidate this Transcript.</span>
                         </th>
                </tr>
            </table>
            <table>
                <tr>
                    <th>
                        <span style="text-decoration: underline;font-size: 20px">  <?php
                            echo isset($teacher->profile->name) ? $teacher->profile->name : '';
                            ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        <?php if (!empty($teacher->signature)):?>
                        <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                          <?php else:?>
                        ________________________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       <?php endif;?>
                   </th>
                    <th><?php if (get_option('transcript_date_of_leaving'.$student->id)):?><span style="text-decoration: underline"><?php echo get_option('transcript_date_of_leaving'.$student->id)?></span><?php else:?>________________________________<?php endif;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>
                        <?php if (!empty($dir)):?>
                            <div style="padding-top: 10%"><img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" width="60px" height="60px"></div>
                       <?php else:?>
                            ________________________________&nbsp;&nbsp;
                        <?php endif;?>
                    </th>
                </tr>
                <tr>
                    <td style="font-size: 22px;text-align: center">Record officer's Name & sign</td>
                    <td style="text-align: center;font-size: 20px">Date of issue</td>
                    <td style="text-align: center;font-size: 20px">Director's sign</td>
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
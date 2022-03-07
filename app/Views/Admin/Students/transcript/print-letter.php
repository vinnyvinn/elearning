<!DocType html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap2.min.css'); ?>"/>
<style>
.fs24{
    font-size: 24px !important;
}
td{
    border: none !important;
}
</style>
</head>
<body>

<?php

//var_dump((new \App\Models\Students())->find(209)->class->id);

$std_id = $student->id;
$departure = (new \App\Models\Departure())->where("student",$std_id)->first();

$directors = (new \App\Models\Teachers())->where('is_director',1)->where('session',active_session())->findAll();
//var_dump($directors);
///exit();
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
$std_ids = array();
$session_ids = array();
$std_conducts = array();
foreach ($student_sessions as $st){
    array_push($std_ids,$st->id);
    array_push($session_ids,$st->session);
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
      <div class="row">
          <div class="col-md-6 offset-1 mt-5">
             <?php $file = get_option('letter_photo'.$student->id)?'uploads/files/'.get_option('letter_photo'.$student->id):'assets/img/default.jpg';?>
              <img src="<?php echo base_url($file)?>" width="300" height="300">
          </div>
          <div class="col-md-4" style="margin-top: 8%">
              <table>
                  <tr>
                   <td class="fs24">ቀን: <span style="text-decoration: underline"><?php echo get_option('date_of_departure'.$student->id) ? date('d/m/Y',strtotime(get_option('date_of_departure'.$student->id))) : '';?></span> <b>ዓ.ም</b></td>
                  </tr>
                  <tr>
                   <td class="fs24">ቁጥር፡ <span style="text-decoration: underline"><?php echo get_option('letter_no'.$student->id)?></span></td>
                  </tr>
              </table>
          </div>
      </div>
      <h1 style="text-align: center;text-decoration: underline">የተማሪ መሸኛ ደብዳቤ</h1>
      <div class="row">
          <div class="col-md-10 offset-1">
              <table class="table fs24">
                  <tr>
                      <td class="fs24" style="padding-top: 0">የተማሪው/ዋ ስም: <span style="text-decoration: underline"><?php echo $student->profile->name;?></span> &nbsp;&nbsp;&nbsp;ዕድሜ፡ <span><?php echo floor((time() - strtotime($student->profile->usermeta('dob'))) / 31556926);?></span></td>
                  </tr>
                  <tr>
                      <td style="padding-top: 0">የተማረበት/ችበት ፕሮግራም፡: <span style="text-decoration: underline"><?php echo get_option('learning_program'.$student->id);?></span></td>
                  </tr>
                  <tr>
                      <td style="padding-top: 0">በት/ቤቱ ለመማር የተመዘገበበት/ችበት: <span style="text-decoration: underline"><?php echo date('Y',strtotime($student->created_at));?></span></td>
                  </tr>
                  <tr>
                      <td style="padding-top: 0">በ: <span style="text-decoration: underline"><?php echo ( get_option('class_when_leaving'.$student->id)) ?  get_option('class_when_leaving'.$student->id) : '_________';?></span> &nbsp;&nbsp;ዓ.ም የነበረበት/ችበት ክፍል፡ <span style="text-decoration: underline"><?php echo date('Y',strtotime($departure->created_at));?></span> &nbsp;&nbsp;የተዛወረበት/ችበት ክፍል፡ <span style="text-decoration: underline"><?php echo get_option('class_to_promote'.$student->id);?></span></td>
                  </tr>
                  <tr>
                      <td>ት/ቤቱ ከተማሪው የሚፈልገው ንብረት ወይንም ቀሪ ክፍያ፡ <span style="text-decoration: underline"><?php echo  get_option('remaining_payment'.$student->id) ? number_format((get_option('remaining_payment'.$student->id)), 2, '.', ',') : '';?></span></td>
                  </tr>
                  <tr>
                      <td style="padding-top: 0">ት/ቤቱን የለቀቀበት/ችበት ቀን፡ <span style="text-decoration: underline"><?php echo get_option('date_of_departure'.$student->id) ? date('d/m/Y',strtotime(get_option('date_of_departure'.$student->id))) : '';?></span> <b>ዓ.ም</b></td>
                  </tr>
                  <tr>
                      <td style="padding-top: 0">የለቀቀበት/ችበት ምክንያት፡ <span style="text-decoration: underline"><?php echo get_option('reason_for_leaving'.$student->id);?></span></td>
                  </tr>
                  <tr>
                      <td style="padding-top: 0">ፀባይ/ባህሪ፡ <span style="text-decoration: underline"><?php echo get_option('student_conduct'.$student->id);?></span></td>
                  </tr>
              </table>
              <h3 style="text-align: center;text-decoration: underline">ተማሪዎቻችን በሄዱበት ሁሉ መልካም እንዲገጥማቸው ት/ቤቱ ከልብ ይመኛል፡፡</h3>
              <h1 style="text-decoration: underline">ማሳሰቢያ</h1>
              <span class="fs24">ይህ የምስክር ወረቀት፡-</span><br>
              <ol class="fs24">
                  <li>የተማሪው/ዋ ፎቶግራፍ ተያይዞ የት/ቤቱ ማህተም ካልታተመበት</li>
                  <li>ስርዝ ድልዝ ካለበት</li>
                  <li>በት/ቤቱ ርዕሰ መምህር ካልተፈረመ ተቀባይነት አይኖረውም፡፡</li>
              </ol>
              <div class="text-right">
                 <h3>የት/ቤቱ ርዕሰ መምህር</h3>
                  <p class="fs24">ስም ፡ <span><?php echo $dir ? $dir->profile->name: '________________________________'?></span>

                  </p>

                  <p class="fs24">ፊርማ
                      <?php if (!empty($dir)):?>
                  <img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" width="60px" height="60px">
              <?php else:?>
                  ________________________________&nbsp;&nbsp;
              <?php endif;?>
                  </p>
                  <p class="fs24">ቀን: <?php echo date('d/m/Y',strtotime($departure->created_at))?></p>
              </div>
          </div>

      </div>
        </div>

    </div>

</body>
</html>
<script>
    window.print();
    setTimeout(() => {
        window.close();
    },2000)

</script>
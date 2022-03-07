<!DocType html>
<html lang="en">
<head>
    <script src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap2.min.css'); ?>"/>
    <style>

        @media print {
            .page-break  { display: block; page-break-before: always; }
        }

        @font-face {
            font-family: Cambria;
            src: url("/assets/fonts/Cambria.ttf");
        }
        .mb-0{
            margin-top: 0;
        }
        .addr{
            padding-left: 15%;
            margin-bottom: 1%;
            margin-top: 2%;
        }
        /*.mb-2p{*/
        /*    margin-bottom: 2%;*/
        /*}*/
        .mb-1p{
            margin-bottom: 1%;
        }
        .fs16{
            font-size: 16px;
        }
        .fs30{
            font-size: 28px;
        }
        .fm-camp{
            font-family: Cambria !important;
        }
        .fs18{
            font-size: 18px;
        }
        .ml-1p{
            margin-left: 1%;
        }
        .mb-1p{
            margin-bottom: 1%;
        }
        .mt-2p{
            margin-top: 2%;
        }
        .fs14{
            font-size: 14px;
        }
        .td-p{
            padding-top: 1% !important;
            padding-bottom: 1% !important;
            border: none !important;
        }
        .underline{
            text-decoration: underline;
        }

        .wp2{
            padding-right: -4%;
        }
        .p_br{
            page-break-before: always !important;
        }
        .vinn-id{
            margin-left: 4.8% !important;
        }
        .vinn2-id{
            margin-left: 5.2% !important;
        }
    </style>
</head>
<body id="pannation-project">
<?php
use App\Models\User;
$n = 0;
$counter = 0;
$c1=0;
$c2=0;
$last_index = 0;
$rem = count($students)%8;
$hasRem = ($rem == 0);

$directors = (new \App\Models\Teachers())->where('is_director',1)->findAll();
for($i =7; $i<count($students); $i+=8) {
    $last_index = $i;
    $id_kg_class = get_option('id_kg_class') ? json_decode(get_option('id_kg_class')) : '';
    $id_el_class = get_option('id_el_class') ? json_decode(get_option('id_el_class')) : '';
    $id_hs_class = get_option('id_hs_class') ? json_decode(get_option('id_hs_class')) : '';
    $phone_ = '';
    if (is_array($id_kg_class) && in_array($students[$i]->class->id,$id_kg_class))
        $phone_ = get_option('id_kg_phone');
    elseif(is_array($id_el_class) && in_array($students[$i]->class->id,$id_el_class))
        $phone_ = get_option('id_el_phone');
    elseif(is_array($id_hs_class) && in_array($students[$i]->class->id,$id_hs_class))
        $phone_ = get_option('id_hs_phone');
?>
<div class="container-fluid">
    <div class="row">
    <?php
    for ($j=$c1; $j<=$i; $j++){
        $counter++;
        $c1++;
        $n++;

$dir = '';
if (isset($students[$j]->class->id)) {
    foreach ($directors as $director) {
        if ($director->director_classes) {
            if (in_array($students[$j]->class->id, json_decode($director->director_classes))) {
                $dir = $director;
            }
        }
    }
}?>
        <div class="col-md-6 <?php if ($c1 % 2 ==0):?>vinn2-id<?php endif;?>" style="max-width: 45.5%;height: 300px;border: 2px solid;margin-left: 2%;margin-top: 2%">
            <div class="row">
                <div class="col-md-3">
                    <?php   $file = get_option('website_logo', FALSE);?>
                    <a href="<?php echo site_url(); ?>">
                     <img class="logo logo-dark" alt="Aspire School Logo"
                             src="<?php echo $file ? base_url('uploads/files/' . $file) : base_url('images/logo.png'); ?>" style="width:100px;height: 94px">
                    </a>
                </div>
                <div class="col-md-9">
                    <?php  $school = substr(get_option('id_school_name', 'Aspire Youth Academy'),0,25);?>
                    <h1 style="font-weight: 900;margin-right: -8%;margin-left: 8%" class="fs30 fm-camp ml-1p"><?php echo $school;?></h1>
                    <?php
                    $address = get_option('id_location') ? substr(get_option('id_location'),0,70) : '';
                    ?>
                    <p class="mb-2p fm-camp addr fs18" style="margin-right: -10% !important;padding-left: 3%"><?php echo $address;?></p>
                    <p class="mb-0 fs18" style="padding-left: 4%;padding-bottom: 0 !important;margin-right: -8%">
                        <?php
                        $phones = $phone_ ? json_decode($phone_) : '';
                        if ($phones && count($phones) > 0){
                            $phones = array_slice($phones,0,2);
                        }

                        if ($phones)
                            $phones = implode('/',$phones);
                        ?>
                        <span class="fm-camp">Tel: <?php echo $phones;?></span>
                    </p>
                </div>
                <div style="width: 100%;border-top:2px solid">
                    <p class="fs16 fm-camp ml-1p wp2" style="">Student Name: <b class="underline"><?php echo $students[$j]->profile->name_user;?></b></p>
                  <?php if (get_option('id_show_date_issued') ==1):?>
                    <p class="fs16 fm-camp  ml-1p" style="margin-top: -1%"><?php echo get_option('id_date_issued_label','Date of issuance')?>: <b class="underline"><?php echo get_option('id_date_issued')?></b></p>
                    <?php endif;?>
                    <?php if (get_option('id_show_expiry_date') ==1):?>
                    <p class="fs16 fm-camp  ml-1p" style="margin-top: -1%"><?php echo get_option('id_expiry_date_label','Expiry Date')?>: <b class="underline"><?php echo get_option('id_expiry_date')?></b></p>
                    <?php endif;?>
                    <p class="fs16 fm-camp  ml-1p" style="margin-top: -1%">I.D.No: <b class="underline"><?php echo $students[$j]->admission_number;?></b>&nbsp;&nbsp;&nbsp; Gender: <b class="underline"><?php echo $students[$j]->profile->gender;?></b></p>
                    <p class="fs16 fm-camp  ml-1p" style="margin-top: -1%">Grade: <b class="underline"><?php echo isset($students[$j]->class->name) ? $students[$j]->class->name : 'N/A';?> </b>&nbsp;&nbsp;&nbsp; Section: <b class="underline"><?php echo $students[$j]->section->name;?></b></p>
                </div>
                <p style="page-break-after: always !important;">
            </div>

        </div>
        <?php }
?>
    </div>
    <p style="page-break-before: always !important;">
</div>
<?php }?>

<?php
if (!$hasRem):
    $c1=0
    ?>
   <div class="container-fluid">
        <div class="row">
            <?php
            for ($j=$last_index+1; $j<count($students); $j++){
                $c1++;
                $dir = '';
                if (isset($students[$j]->class->id)) {
                    foreach ($directors as $director) {
                        if ($director->director_classes) {
                            if (in_array($students[$j]->class->id, json_decode($director->director_classes))) {
                                $dir = $director;
                            }
                        }
                    }
                }?>
                <div class="col-md-6 <?php if ($c1 % 2 ==0):?>vinn2-id<?php endif;?>" style="max-width: 45.5%;height: 300px;border: 2px solid;margin-left: 2%;margin-top: 2%">
                    <div class="row">
                        <div class="col-md-3">
                            <?php   $file = get_option('website_logo', FALSE);?>
                            <a href="<?php echo site_url(); ?>">
                                <img class="logo logo-dark" alt="Aspire School Logo"
                                     src="<?php echo $file ? base_url('uploads/files/' . $file) : base_url('images/logo.png'); ?>" style="width:100px;height: 94px">
                            </a>
                        </div>
                        <div class="col-md-9">
                            <?php  $school = substr(get_option('id_school_name', 'Aspire Youth Academy'),0,25);?>
                            <h1 style="font-weight: 900;margin-right: -8%;margin-left: 8%" class="fs30 fm-camp ml-1p"><?php echo $school;?></h1>
                            <?php
                            $address = get_option('id_location') ? substr(get_option('id_location'),0,70) : '';
                            ?>
                            <p class="mb-2p fm-camp addr fs18" style="margin-right: -10% !important;padding-left: 3%"><?php echo $address;?></p>
                            <p class="mb-0 fs18" style="padding-left: 4%;padding-bottom: 0 !important;margin-right: -8%">
                                <?php
                                $phones = $phone_ ? json_decode($phone_) : '';
                                if ($phones && count($phones) > 0){
                                    $phones = array_slice($phones,0,2);
                                }

                                if ($phones)
                                    $phones = implode('/',$phones);
                                ?>
                                <span class="fm-camp">Tel: <?php echo $phones;?></span>
                            </p>
                        </div>
                        <div style="width: 100%;border-top:2px solid">
                            <p class="fs16 fm-camp ml-1p wp2" style="">Student Name: <b class="underline"><?php echo $students[$j]->profile->name_user;?></b></p>
                          <?php if (get_option('id_show_date_issued') ==1):?>
                            <p class="fs16 fm-camp  ml-1p" style="margin-top: -1%"><?php echo get_option('id_date_issued_label','Date of issuance')?>: <b class="underline"><?php echo get_option('id_date_issued')?></b></p>
                            <?php endif;?>
                            <?php if (get_option('id_show_expiry_date') ==1):?>
                            <p class="fs16 fm-camp  ml-1p" style="margin-top: -1%"><?php echo get_option('id_expiry_date_label','Expiry Date')?>: <b class="underline"><?php echo get_option('id_expiry_date')?></b></p>
                            <?php endif;?>
                           <p class="fs16 fm-camp  ml-1p" style="margin-top: -1%">I.D.No: <b class="underline"><?php echo $students[$j]->admission_number;?></b>&nbsp;&nbsp;&nbsp; Gender: <b class="underline"><?php echo $students[$j]->profile->gender;?></b></p>
                            <p class="fs16 fm-camp  ml-1p" style="margin-top: -1%">Grade: <b class="underline"><?php echo isset($students[$j]->class->name) ? $students[$j]->class->name : 'N/A';?> </b>&nbsp;&nbsp;&nbsp; Section: <b class="underline"><?php echo $students[$j]->section->name;?></b></p>
                        </div>
                        <p style="page-break-after: always !important;">
                    </div>

                </div>
            <?php }
            ?>
        </div>
        <p style="page-break-before: always !important;">
    </div>
<?php endif;?>

<?php for($i =7; $i<count($students); $i+=8) {
?>
<div class="container-fluid">
    <div class="row">
        <?php for ($k=$c2; $k<=$i;$k++){
              $c2++;
              $dir = '';
              if (isset($students[$k]->class->id)) {
                  foreach ($directors as $director) {
                      if ($director->director_classes) {
                          if (in_array($students[$k]->class->id, json_decode($director->director_classes))) {
                              $dir = $director;
                          }
                      }
                  }
              }

               ?>
                <div class="col-md-6 <?php if ($c2 %2 == 0):?> vinn-id <?php else:?> fallback-vinn<?php endif;?>" style="margin-left: 2%;max-width: 45.5%;height: 300px;margin-top: 2%;">
                    <p class="fs14 mb-0 wp2"><?php echo get_option('id_parent')?>: <b class="underline"><?php echo  (isset($students[$k]->parent->name_user) && get_option('id_autofill')) ? $students[$k]->parent->name_user :'____________________________'?></b></p>
                    <p class="fs14 mb-0 wp2" style="margin-right: -8% !important;"><?php echo get_option('id_address').'-'.get_option('id_subcity')?>: <b class="underline"><?php echo (is_object($students[$k]->parent)  && get_option('id_autofill') ==1) ? $students[$k]->parent->usermeta('subcity') : '_________';?> </b>&nbsp;<?php echo get_option('id_woreda')?>: <b class="underline"><?php echo (is_object($students[$k]->parent) && get_option('id_autofill')) ? $students[$k]->parent->usermeta('woreda') : '________';?></b>  &nbsp;<?php echo get_option('id_house_no');?> : <b class="underline"><?php echo (is_object($students[$k]->parent)&& get_option('id_autofill')) ? $students[$k]->parent->usermeta('house_number') :'_______'?></b></p>
                    <p class="fs14 mb-0 wp2"><?php echo get_option('id_phone1')?>: <b class="underline"><?php echo (is_object($students[$k]->parent) && get_option('id_autofill')) ? $students[$k]->parent->usermeta('mobile_phone_number') :'________________' ;?></b> &nbsp;<?php echo get_option('id_phone2')?>: <b class="underline"> <?php echo (is_object($students[$k]->parent) && get_option('id_autofill')) ? $students[$k]->parent->usermeta('mobile_phone_work') :'________________';?></b></p>
                    <p class="text-center fs16" style="margin-bottom: 0.5%;"><b class="underline"><?php echo get_option('id_header')?></b></p>
                    <?php $text = get_option('id_text') ? json_decode(get_option('id_text')) : '';?>
                    <?php if ($text):
                        ?>
                        <ul class="mb-0">
                            <?php
                            foreach ($text as $item):
                                ?>
                                <li>
                                    <b class="fs14"> <?php echo $item;?></b>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                    <p class="fs16 mb-0" style="position: absolute;bottom: 0"><?php echo get_option('id_sign')?>:
                        <?php if (isset($dir) && !empty($dir)):?>
                            <span class="underline">   <img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" style="width: 15%"></span>
                        <?php else: ?>
                            -------------------
                        <?php endif;?>
                    </p>
                </div>

                <?php }?>
    </div>
    <p style="page-break-before: always !important;">
</div>
<?php }?>
<?php
if (!$hasRem):
$c2=0
?>
    <div class="container-fluid">
        <div class="row">
            <?php for ($k=$last_index+1; $k<count($students);$k++){
                $c2++;
                $dir = '';
                if (isset($students[$k]->class->id)) {
                    foreach ($directors as $director) {
                        if ($director->director_classes) {
                            if (in_array($students[$k]->class->id, json_decode($director->director_classes))) {
                                $dir = $director;
                            }
                        }
                    }
                }

                ?>
                <div class="col-md-6 <?php if ($c2 %2 == 0):?> vinn-id <?php else:?> fallback-vinn<?php endif;?>" style="margin-left: 2%;max-width: 45.5%;height: 300px;margin-top: 2%;">
                    <p class="fs14 mb-0 wp2"><?php echo get_option('id_parent')?>: <b class="underline"><?php echo  (isset($students[$k]->parent->name_user) && get_option('id_autofill')) ? $students[$k]->parent->name_user :'____________________________'?></b></p>
                    <p class="fs14 mb-0 wp2" style="margin-right: -8% !important;"><?php echo get_option('id_address').'-'.get_option('id_subcity')?>: <b class="underline"><?php echo (is_object($students[$k]->parent)  && get_option('id_autofill') ==1) ? $students[$k]->parent->usermeta('subcity') : '_________';?> </b>&nbsp;<?php echo get_option('id_woreda')?>: <b class="underline"><?php echo (is_object($students[$k]->parent) && get_option('id_autofill')) ? $students[$k]->parent->usermeta('woreda') : '________';?></b>  &nbsp;<?php echo get_option('id_house_no');?> : <b class="underline"><?php echo (is_object($students[$k]->parent)&& get_option('id_autofill')) ? $students[$k]->parent->usermeta('house_number') :'_______'?></b></p>
                    <p class="fs14 mb-0 wp2"><?php echo get_option('id_phone1')?>: <b class="underline"><?php echo (is_object($students[$k]->parent) && get_option('id_autofill')) ? $students[$k]->parent->usermeta('mobile_phone_number') :'________________' ;?></b> &nbsp;<?php echo get_option('id_phone2')?>: <b class="underline"> <?php echo (is_object($students[$k]->parent) && get_option('id_autofill')) ? $students[$k]->parent->usermeta('mobile_phone_work') :'________________';?></b></p>
                    <p class="text-center fs16" style="margin-bottom: 0.5%;"><b class="underline"><?php echo get_option('id_header')?></b></p>
                    <?php $text = get_option('id_text') ? json_decode(get_option('id_text')) : '';?>
                    <?php if ($text):
                        ?>
                        <ul class="mb-0">
                            <?php
                            foreach ($text as $item):
                                ?>
                                <li>
                                    <b class="fs14"> <?php echo $item;?></b>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                    <p class="fs16 mb-0" style="position: absolute;bottom: 0"><?php echo get_option('id_sign')?>:
                        <?php if (isset($dir) && !empty($dir)):?>
                            <span class="underline">   <img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" style="width: 15%"></span>
                        <?php else: ?>
                            -------------------
                        <?php endif;?>
                    </p>
                </div>

            <?php }?>
        </div>
        <p style="page-break-before: always !important;">
    </div>
<?php endif;?>
</body>
</html>


<script>
//
 window.print();
  setTimeout(() => {
      window.close();
  },3000)
swapElements();

function swapElements() {
    var items = document.getElementsByClassName("vinn-id");
    var items2 = document.getElementsByClassName("fallback-vinn");
    for (let i=0; i<items.length; i++){
        var elem1 = items[i].innerHTML;
        var elem2 = items2[i].innerHTML;
        document.getElementsByClassName("vinn-id")[i].innerHTML = elem2;
        document.getElementsByClassName("fallback-vinn")[i].innerHTML = elem1;

    }
}
</script>
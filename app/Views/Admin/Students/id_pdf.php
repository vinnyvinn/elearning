<!DocType html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap2.min.css'); ?>"/>
    <style>
        @font-face {
            font-family: Cambria;
            src: url("/assets/fonts/Cambria.ttf");
        }
        .mb-0{
            margin-top: 0;
        }
        .addr{
            padding-left: -4%;
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
            font-size: 12px;
        }
        .fs30{
            font-size: 18px;
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
        .fs13{
            font-size: 12px;
        }
        .fs12{
            font-size: 13px;
        }
        .ln5{
            line-height: 1;
            margin-left: -4%;
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
            padding-right: -5%;
        }
    </style>
</head>
<body id="pannation-project">
<?php

$directors = (new \App\Models\Teachers())->where('is_director',1)->findAll();
$dir = '';
foreach ($directors as $director){
    if ($director->director_classes) {
        if (in_array($student->class->id, json_decode($director->director_classes))) {
            $dir = $director;
        }
    }
}

$id_kg_class = get_option('id_kg_class') ? json_decode(get_option('id_kg_class')) : '';
$id_el_class = get_option('id_el_class') ? json_decode(get_option('id_el_class')) : '';
$id_hs_class = get_option('id_hs_class') ? json_decode(get_option('id_hs_class')) : '';
$phone_ = '';
if (is_array($id_kg_class) && in_array($student->class->id,$id_kg_class))
    $phone_ = get_option('id_kg_phone');
elseif(is_array($id_el_class) && in_array($student->class->id,$id_el_class))
    $phone_ = get_option('id_el_phone');
elseif(is_array($id_hs_class) && in_array($student->class->id,$id_hs_class))
    $phone_ = get_option('id_hs_phone');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" style="max-width: 39%;height: 212px;border: 2px solid">
            <div class="row">
                <div class="col-md-3">
                    <?php   $file = get_option('website_logo', FALSE);?>
                    <a href="<?php echo site_url(); ?>">
                        <img class="logo logo-dark" alt="Aspire School Logo"
                             src="<?php echo $file ? base_url('uploads/files/' . $file) : base_url('images/logo.png'); ?>" style="width:68px;height: 68px">
                    </a>
                </div>
                <div class="col-md-9">
                    <div>
                        <?php
                        $school = substr(get_option('id_school_name', 'Aspire Youth Academy'),0,25);?>
                        <h1 style="font-weight: 900;margin-right: -8%;margin-left: 6%;margin-bottom: 0" class="fs30 fm-camp ml-1p"><?php echo $school; ?></h1>
                    </div>
                    <?php
                    $address = get_option('id_location') ? substr(get_option('id_location'),0,70) : '';
                    ?>
                    <p class="mb-2p fm-camp addr fs16" style="margin-right: -10% !important;margin-left: 1%"><?php echo $address;?></p>
                    <p class="mb-0 fs20" style="padding-bottom: 0 !important;margin-left: 3%;margin-right: -8%;font-size: 14px">
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
                <div style="width: 100%;border-top:2px solid;margin-top: 1%">
                    <p class="fs14 fm-camp ml-1p wp2" style="margin-top:0;margin-bottom: 0">Student Name: <b class="underline"><?php echo $student->profile->name_user;?></b></p>
                    <?php if (get_option('id_show_date_issued') ==1):?>
                    <p class="fs14 fm-camp  ml-1p" style="margin-top:0;margin-bottom: 0"><?php echo get_option('id_date_issued_label','Date of issuance')?>: <b class="underline"><?php echo get_option('id_date_issued')?></b></p>
                    <?php endif;?>
                    <?php if (get_option('id_show_expiry_date') ==1):?>
                    <p class="fs14 fm-camp  ml-1p" style="margin-top:0;margin-bottom: 0"><?php echo get_option('id_expiry_date_label','Expiry Date')?>: <b class="underline"><?php echo get_option('id_expiry_date')?></b></p>
                    <?php endif;?>
                    <p class="fs14 fm-camp  ml-1p" style="margin-top:0;margin-bottom: 0">I.D.No: <b class="underline"><?php echo $student->admission_number;?></b>&nbsp;&nbsp;&nbsp; Gender: <b class="underline"><?php echo $student->profile->gender;?></b></p>
                    <p class="fs14 fm-camp  ml-1p" style="margin-top:0;margin-bottom: 0">Grade: <b class="underline"><?php echo isset($student->class->name) ? $student->class->name : 'N/A';?> </b>&nbsp;&nbsp;&nbsp; Section: <b class="underline"><?php echo $student->section->name;?></b></p>
                </div>
            </div>
            <div class="col-md-6" style="margin-left: 2%;max-width: 39%;height: 212px">
            </div>
        </div>

    </div>
    <p style="page-break-after: always !important;">
    <div class="row">
        <div class="col-md-6" style="max-width: 39%;height: 212px;">
        </div>
        <div class="col-md-6" style="border: 2px solid;margin-left: 2%;max-width: 39%;height: 212px">
            <p class="fs13 mb-0 wp2" style="font-size: 10px;margin-left: -2%;margin-right: -2%"><?php echo get_option('id_parent')?>: <b class="underline"><?php echo (isset($student->parent->name_user) && get_option('id_autofill')) ? $student->parent->name_user :'____________________________'?></b></p>
            <p class="mb-0 wp2 fs13" style="font-size: 10px;margin-left: -2%;margin-right: -8%"><?php echo get_option('id_address').'-'.get_option('id_subcity')?>: <b class="underline"><?php echo (is_object($student->parent)  && get_option('id_autofill') ==1) ? $student->parent->usermeta('subcity') : '________';?> </b>&nbsp;<?php echo get_option('id_woreda')?>: <b class="underline"><?php echo (is_object($student->parent) && get_option('id_autofill')) ? $student->parent->usermeta('woreda') : '________';?></b>  &nbsp;<?php echo get_option('id_house_no');?> : <b class="underline"><?php echo (is_object($student->parent)&& get_option('id_autofill')) ? $student->parent->usermeta('house_number') :'_____'?></b></p>
            <p class="fs13 mb-0 wp2"  style="font-size: 10px;margin-left: -2%;margin-right: -2%"><?php echo get_option('id_phone1')?>: <b class="underline"><?php echo (is_object($student->parent) && get_option('id_autofill')) ? $student->parent->usermeta('mobile_phone_number') :'________________' ;?></b> &nbsp;<?php echo get_option('id_phone2')?>: <b class="underline"> <?php echo (is_object($student->parent) && get_option('id_autofill')) ? $student->parent->usermeta('mobile_phone_work') :'________________';?></b></p>
            <p class="text-center fs16" style="margin-bottom: 0.5%;"><b class="underline"><?php echo get_option('id_header')?></b></p>
            <?php $text = get_option('id_text') ? json_decode(get_option('id_text')) : '';?>
            <?php if ($text):
                ?>
                <ul class="mb-0">
                    <?php
                    foreach ($text as $item):
                        ?>
                        <li class="ln5" style="margin-right: -8%">
                            <b style="font-size: 10px;"> <?php echo $item;?></b>
                        </li>
                    <?php endforeach;?>
                </ul>
            <?php endif;?>
            <p class="mb-0" style="position: absolute;bottom: 0;font-size: 10px"><?php echo get_option('id_sign')?>:
                <?php if (isset($dir) && !empty($dir)):?>
                    <span class="underline">   <img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" style="width: 15%"></span>
                <?php else: ?>
                    -----------------
                <?php endif;?>
            </p>
        </div>
    </div>
</div>


</body>
</html>


<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = '<?php echo $student->profile->name.'-'.$student->id;?>';

    var element = document.getElementById('pannation-project');
    var opt = {
        margin:       [0.2,0.2,0,-0.9],
        filename:     name+'.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:          { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    // New Promise-based usage:
    //  html2pdf().set(opt).from(element).save();

    // // Old monolithic-style usage:
    html2pdf(element, opt)
        .then(res =>{
            console.log('finished')
            setTimeout(()=>{
                window.close();
            },2000)

        })
    //
    // window.print();
    // setTimeout(() => {
    //     window.close();
    // },3000)


</script>

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
            padding-left: 8%;
            margin-bottom: 1%;
            margin-top: 2%;
            margin-right: -10% !important;
        }
        .tel{
            padding-left: 10%;
            margin-bottom: 0;
            margin-top: 0;
        }
        .sch{
            padding-left: 8%;
            margin-right: -8%;
            margin-bottom: 0;
            margin-top: 0;
            font-weight: 900;
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
            font-size: 14px;
        }
        .ln5{
            line-height: 1;
            margin-left: -3%;
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
            font-size: 12px;
        }
        .fs13{
            font-size: 13px;
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
        .vinn-id{
            margin-left: 3.7% !important;
        }
        .vinn2-id{
            margin-left: 3.2% !important;
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
?>
<div class="container-fluid">
    <div class="row">
        <?php
        for($i =0; $i<count($students); $i++) {
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


        for ($j=$c1; $j<=$i; $j++){
            $counter++;
            $c1++;
            $user = (new User())->find($students[$j]->id);
            $n++;

            $directors = (new \App\Models\Teachers())->where('is_director',1)->findAll();
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
        <div class="col-md-6 <?php if ($c1 % 2 ==0):?>vinn2-id<?php endif;?>" style="max-width: 39%;height: 212px;border: 2px solid;margin-top: 1%;margin-left: 1%">
            <div class="row">
                <div class="col-md-3" style="margin-left: -4%">
                    <?php   $file = get_option('website_logo', FALSE);?>
                    <a href="<?php echo site_url(); ?>">
                        <img class="logo logo-dark" alt="Aspire School Logo"
                             src="<?php echo $file ? base_url('uploads/files/' . $file) : base_url('images/logo.png'); ?>" style="width:68px;height: 68px">
                    </a>
                </div>
                <div class="col-md-9">
                    <?php
                    $school = substr(get_option('id_school_name', 'Aspire Youth Academy'),0,25);?>
                    <h1  style="font-weight: 900;margin-right: -10%;margin-left: 6%;margin-bottom: 0" class="fs30 mb-0 fm-camp sch"><?php echo $school; ?></h1>
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
                <div style="width: 100%;border-top:2px solid">
                    <p class="fs14 fm-camp ml-1p wp2" style="margin-top: 1%;margin-bottom: 1%">Student Name: <b class="underline"><?php echo $students[$j]->profile->name_user;?></b></p>
                 <?php if (get_option('id_show_date_issued') ==1):?>
                    <p class="fs14 fm-camp  ml-1p" style="margin-top: 1%;margin-bottom: 1%"><?php echo get_option('id_date_issued_label','Date of issuance')?>: <b class="underline"><?php echo get_option('id_date_issued')?></b></p>
            <?php endif;?>
            <?php if (get_option('id_show_expiry_date') ==1):?>
                    <p class="fs14 fm-camp  ml-1p" style="margin-top: 1%;margin-bottom: 1%"><?php echo get_option('id_expiry_date_label','Expiry Date')?>: <b class="underline"><?php echo get_option('id_expiry_date')?></b></p>
            <?php endif;?>
                    <p class="fs14 fm-camp  ml-1p" style="margin-top: 1%;margin-bottom: 1%">I.D.No: <b class="underline"><?php echo $students[$j]->admission_number;?></b>&nbsp;&nbsp;&nbsp; Gender: <b class="underline"><?php echo $students[$j]->profile->gender;?></b></p>
                    <p class="fs14 fm-camp  ml-1p" style="margin-top: 1%;margin-bottom: 1%">Grade: <b class="underline"><?php echo isset($students[$j]->class->name) ? $students[$j]->class->name : 'N/A';?> </b>&nbsp;&nbsp;&nbsp; Section: <b class="underline"><?php echo $students[$j]->section->name;?></b></p>
                </div>
              </div>
        </div>
        <?php }}?>
    </div>

</div>

<p style="page-break-after: always !important;">
<div class="container-fluid" style="margin-top: -1.8%">
    <div class="row">
        <?php
        for($i =0; $i<count($students); $i++) {
        for ($k=$c2; $k<=$i;$k++){
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
        <div class="col-md-6 <?php if ($c2 %2 == 0):?> vinn-id<?php else:?> fallback-vinn<?php endif;?>" style="margin-left: 3%;max-width: 39%;height: 212px;margin-top: 1%">
            <p class="fs13 mb-0 wp2" style="font-size: 10px;margin-left: -2%;margin-right: -2%"><?php echo get_option('id_parent')?>: <b class="underline"><?php echo (isset($students[$k]->parent->name_user) && get_option('id_autofill')) ? $students[$k]->parent->name_user :'____________________________'?></b></p>
            <p class="mb-0 wp2 fs13" style="font-size: 10px;margin-left: -2%;margin-right: -8%"><?php echo get_option('id_address').'-'.get_option('id_subcity')?>: <b class="underline"><?php echo (is_object($students[$k]->parent)  && get_option('id_autofill') ==1) ? $students[$k]->parent->usermeta('subcity') : '_______';?> </b>&nbsp;<?php echo get_option('id_woreda')?>: <b class="underline"><?php echo (is_object($students[$k]->parent) && get_option('id_autofill')) ? $students[$k]->parent->usermeta('woreda') : '______';?></b>  &nbsp;<?php echo get_option('id_house_no');?> : <b class="underline"><?php echo (is_object($students[$k]->parent)&& get_option('id_autofill')) ? $students[$k]->parent->usermeta('house_number') :'____'?></b></p>
            <p class="fs13 mb-0 wp2"  style="font-size: 10px;margin-left: -2%;margin-right: -8%"><?php echo get_option('id_phone1')?>: <b class="underline"><?php echo (is_object($students[$k]->parent) && get_option('id_autofill')) ? $students[$k]->parent->usermeta('mobile_phone_number') :'______________' ;?></b> &nbsp;<?php echo get_option('id_phone2')?>: <b class="underline"> <?php echo (is_object($students[$k]->parent) && get_option('id_autofill')) ? $students[$k]->parent->usermeta('mobile_phone_work') :'______________';?></b></p>
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
            <p class="fs16 mb-0" style="font-size: 10px;position: absolute;bottom: 0"><?php echo get_option('id_sign')?>:
                <?php if (isset($dir) && !empty($dir)):?>
                    <span class="underline">   <img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" style="width: 15%"></span>
                <?php else: ?>
                    ----------------------------
                <?php endif;?>
            </p>
        </div>
        <?php }}?>
    </div>
</div>

</body>
</html>

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
    var name = "<?php echo 'bulk ids';?>";

    var element = document.getElementById('pannation-project');
    var opt = {
        margin:       [0,0.2,0,-0.9],
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
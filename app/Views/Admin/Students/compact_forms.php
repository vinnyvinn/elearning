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

<div class="container-fluid">
    <div class="text-center" style="text-decoration: underline">
        <h1>USERNAME & PASSWORD</h1>
        <h3><?php echo $students[0]->class->name.' - '.$students[0]->section->name;?></h3>
    </div>
    <div class="row">
        <?php
        $n=0;
        foreach($students as $student){
            $n++;
            ?>

        <div class="col-md-6">
            <div style="border: #4848ff 1px solid; padding: 5px">
                School Portal URL: <a href="<?php echo site_url(route_to('auth.login')); ?>"><?php echo site_url(route_to('auth.login')); ?></a><br/>
                <b>Student Name: </b> <?php echo $student->profile->name; ?><br>
                <b>Student Username: </b> <?php echo $student->profile->username; ?>
                <b>Student Password: </b> <?php echo $student->profile->usermeta('password', FALSE) ? $student->profile->usermeta('password') : $student->parent->usermeta('mobile_phone_number').'s' ; ?>
                <br/>
                <b>Parent Username: </b> <?php echo $student->parent->username; ?>
                <b>Parent Password: </b> <?php echo $student->parent->usermeta('password', FALSE) ? $student->parent->usermeta('password') : $student->parent->usermeta('mobile_phone_number'); ?>
            </div>
        </div>
        <?php if ($n%2 ==0 ){?>
        <p style="page-break-after: always !important;">
            <?php }?>
        <?php
            }?>
    </div>
</div>

</body>
</html>


<script>
//
  window.print();
  setTimeout(() => {
      window.close();
  },3000)
</script>
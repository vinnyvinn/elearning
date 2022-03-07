<!DocType html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/pdf.css'); ?>"/>
</head>
<body id="pannation-project" style="background: transparent !important;">
<?php foreach ($students as $student):?>
    <div>
<table class="table">
    <tbody>
    <tr>
        <td class="pr-20">
            <?php $file = get_option( 'website_logo'); ?>
            <a href="<?php echo site_url(); ?>">
                <img class="logo logo-dark" alt="Aspire School Logo"
                     src="<?php echo get_logo(); ?>" style="width:100px; !important;">
            </a>
        </td>
        <td>
            <h3><?php echo get_option('id_school_name', 'Aspire Youth Academy'); ?></h3>
            <?php
            $phones = get_option('website_phone') ? json_decode(get_option('website_phone')) : '';
            if ($phones && count($phones) > 0){
                $phones = array_slice($phones,0,2);
            }

            if ($phones)
                $phones = implode(' | ',$phones);
            ?>
            <h5><?php echo $phones?></h5>
            <h5><?php echo get_option('website_location');?></h5>
        </td>
    </tr>
    </tbody>
</table>
<hr/>

    <h4 class="mb-0">Admission Form</h4>
    <table class="table">
        <tbody>
        <tr>
            <td style="padding-right: 1px" class="col-2 image-border">
                <img class="img-thumbnail img-center border-light" src="<?php echo $student->profile->avatar2; ?>" style="width: 30%;margin-top: 1%"/>
            </td>
            <td class="col-10" style="padding: 5px">
                <table>
                    <tr>
                        <td>
                            <b>NAME: </b><?php echo $student->profile->name; ?><br/>
                        </td>
                        <td>
                            <b>STUDENT ID: </b><?php echo $student->admission_number; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>CLASS: </b><?php echo $student->class->name; ?>
                        </td>
                        <td>
                            <b>SECTION: </b><?php echo $student->section->name; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Date of Birth: </b><?php echo $student->profile->usermeta('dob'); ?>
                        </td>
                        <td>
                            <b>Gender: </b><?php echo $student->profile->gender; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Date of Admission: </b><?php echo $student->admission_date; ?>
                        </td>
                        <td>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <h4>Parent/Guardian Contact Information</h4>
    <table>
        <tbody>
        <tr>
            <td><b>NAME: </b><?php echo $student->parent->name; ?></td>
        </tr>
        <tr>
            <td>
                <b>MOBILE PHONE: </b><?php echo $student->parent->usermeta('mobile_phone_number'); ?>
            </td>
            <td>
                <b>WORK PHONE: </b><?php echo $student->parent->usermeta('mobile_phone_work'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Subcity: </b><?php echo $student->parent->usermeta('subcity'); ?>
            </td>
            <td>
                <b>Woreda: </b><?php echo $student->parent->usermeta('woreda'); ?>
            </td>
            <td>
                <b>House Number: </b><?php echo $student->parent->usermeta('house_number'); ?>
            </td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <!-- TODO: Additional information, including admission exam results, users passwords, etc -->
    <h4>Admission Results</h4>
    <table style="width: 100%;" class="with-border">
        <tr>
            <td>
                <b>Amharic</b>
                <?php echo $student->profile->usermeta('amharic', '-'); ?>
            </td>
            <td>
                <b>English</b>
                <?php echo $student->profile->usermeta('english', '-'); ?>
            </td>
            <td>
                <b>Math</b>
                <?php echo $student->profile->usermeta('math', '-'); ?>
            </td>
            <td>
                <b>Social Science</b>
                <?php echo $student->profile->usermeta('social_science', '-'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>General Science</b>
                <?php echo $student->profile->usermeta('general_science', '-'); ?>
            </td>
            <td>
                <b>Biology</b>
                <?php echo $student->profile->usermeta('biology', '-'); ?>
            </td>
            <td>
                <b>Physics</b>
                <?php echo $student->profile->usermeta('physics', '-'); ?>
            </td>
            <td>
                <b>Chemistry</b>
                <?php echo $student->profile->usermeta('chemistry', '-'); ?>
            </td>
        </tr>
    </table>
    <h5>English Skills</h5>
    <table class="table with-border" style="width: 100%">
        <thead>
        <tr>
            <th>Speaking</th>
            <th>Listening</th>
            <th>Writing</th>
            <th>Reading</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align: center">
                <?php
                echo ucwords(str_replace('_', ' ', $student->profile->usermeta('eng_speaking', '-')));
                ?>
            </td>
            <td style="text-align: center">
                <?php
                echo ucwords(str_replace('_', ' ', $student->profile->usermeta('eng_listening', '-')));
                ?>
            </td>
            <td style="text-align: center">
                <?php
                echo ucwords(str_replace('_', ' ', $student->profile->usermeta('eng_writing', '-')));
                ?>
            </td>
            <td style="text-align: center">
                <?php
                echo ucwords(str_replace('_', ' ', $student->profile->usermeta('eng_reading', '-')));
                ?>
            </td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <h4>School Portal</h4>
    <div style="border: #4848ff 1px solid; padding: 5px">
        School Portal URL: <a href="<?php echo site_url(route_to('auth.login')); ?>"><?php echo site_url(route_to('auth.login')); ?></a><br/>
        <b>Student Username: </b> <?php echo $student->profile->username; ?>
        <b>Student Password: </b> <?php echo $student->profile->usermeta('password', FALSE) ? $student->profile->usermeta('password') : $student->parent->usermeta('mobile_phone_number').'s' ; ?>
        <br/>
        <b>Parent Username: </b> <?php echo $student->parent->username; ?>
        <b>Parent Password: </b> <?php echo $student->parent->usermeta('password', FALSE) ? $student->parent->usermeta('password') : $student->parent->usermeta('mobile_phone_number'); ?>
    </div>
</div>
<?php endforeach;?>
</body>
</html>



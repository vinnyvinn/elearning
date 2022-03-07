<?php
$phones = get_option('student_phone') ? json_decode(get_option('student_phone')) : '';
$phones = isset($phones) ? implode(" or ", $phones) : '';
?>
<div class="container">
    <h1 class="title text-center mt-5">STUDENT REGISTRATION FORM</h1>
    <h5 class=" text-center">FOR ANY ASSISTANCE OR TO REGISTER
        BY PHONE; CALL: <?php echo $phones;?></h5>
</div>
<section id="contact" class="contact section">
    <div class="container">
        <form method="post" class="ajaxForm" loader="true" action="<?php echo current_url(); ?>" data-parsley-validate enctype="multipart/form-data">
            <input type="hidden" name="form" value="student" />
            <div class="row">
                <div class="col-md-3">
                    <div class="pb-3">
                        <img src="<?php echo base_url('assets/img/default.jpg'); ?>" id="imgPreview" alt="Image Preview" class="img-thumbnail"/>
                    </div>
                    <div class="form-group">
                        <label for="pic">Passport Size Photo</label>
                        <input type="file" id="profPic" name="profile_pic" accept="image/*" class="form-control form-control-sm" />
                        <small class="text-danger">Maximum file size: 5MB</small>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sname">First Name <span class="text-danger">*</span></label>
                                <input type="text" id="sname" name="surname" class="form-control form-control-sm"
                                       value="<?php echo old('surname'); ?>" placeholder="First Name" required/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fname">Father's Name <span class="text-danger">*</span></label>
                                <input type="text" id="fname" name="first_name" class="form-control form-control-sm"
                                       value="<?php echo old('first_name'); ?>" placeholder="Father's Name" required/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lname">Grandfather's Name <span class="text-danger">*</span></label>
                                <input type="text" id="lname" name="last_name"
                                       value="<?php echo old('last_name'); ?>" class="form-control form-control-sm"
                                       placeholder="Grandfather's Name" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm select2" data-toggle="select" name="gender" required>
                                    <option value=""> -- Please select --</option>
                                    <option <?php echo old('gender') == 'Male' ? 'selected' : ''; ?> value="Male">
                                        Male
                                    </option>
                                    <option <?php echo old('gender') == 'Female' ? 'selected' : ''; ?>
                                            value="Female">Female
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dob">Date of Birth (dd/mm/yyyy) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm datepicker" name="dob" required
                                       value="<?php echo old('dob'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="hidden" name="existing_student" value="<?php echo (isset($_GET['new']) && $_GET['new']==0) ? 1 : 0?>">
                        </label>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grade">Grade (To enroll in) <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm select2" id="" data-toggle="select" name="class" required>
                                    <option value=""> -- Please select --</option>
                                    <?php
                                    $classes = getSession()->classes->orderBy('id', 'DESC')->findAll();
                                    if($classes && count($classes) > 0 ) {
                                        foreach ($classes as $class) {
                                            ?>
                                            <option <?php echo old('class') == $class->id ? 'selected' : ''; ?> value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="doa">Date of Admission (mm/dd/yyyy) <span class="text-danger">*</span></label>
                                <input type="text" name="admission_date" class="form-control form-control-sm datepicker" value="<?php echo old('admission_date', date('d/m/Y')); ?>" required />
                            </div>
                        </div>
                        <?php if (isset($_GET['new']) && $_GET['new']==0):?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="admission_number">Student ID Number <span class="text-danger text-xs">*</span></label>
                                <input type="text" name="admission_number" class="form-control form-control-sm" value="<?php echo old('admission_number'); ?>" />
                            </div>
                        </div>
                        <?php endif;?>

<!--                    <div class="form-group">-->
<!--                        <label for="language">Language Spoken At Home <span class="text-danger">*</span></label>-->
<!--                        <div class="">-->
<!--                            <input type="text" class="form-control form-control-sm" id="language" name="language" value="--><?php //echo old('language'); ?><!--" placeholder="Language spoken at home" required="">-->
<!--                        </div>-->
<!--                    </div>-->
                        <?php if (isset($_GET['new']) && $_GET['new']==1):?>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="prev_school">Previous School <span class="text-danger text-xs">* Required if you are not enrolled in our school</span></label>
                           <div class="">
                               <input type="text" class="form-control form-control-sm" id="prev_school" name="previous_school" value="<?php echo old('previous_school'); ?>" placeholder="Previous School">
                           </div>
                       </div>
                   </div>
                    <?php endif;?>
                </div>
            </div>
            </div>
            <hr class="pt-0 mt--1" />
            <h2>Parent/Guardian Information</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="pb-3">
                        <img src="<?php echo base_url('assets/img/default.jpg'); ?>" id="ParentImgPreview" alt="Image Preview" class="img-thumbnail"/>
                    </div>
                    <div class="form-group">
                        <label for="pic">Passport Size Image</label>
                        <input type="file" id="parentProfPic" name="parent_profile_pic" accept="image/*" class="form-control form-control-sm"/>
                        <small class="text-danger">Maximum file size: 5MB</small>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sname">First Name</label>
                                <input type="text" id="sname" name="parent_surname" class="form-control form-control-sm"
                                       value="<?php echo old('parent_surname'); ?>" placeholder="First Name" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fname">Father's Name <span class="text-danger">*</span></label>
                                <input type="text" id="fname" name="parent_first_name" class="form-control form-control-sm"
                                       value="<?php echo old('parent_first_name'); ?>" placeholder="Father's Name" required/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lname">Grandfather's Name <span class="text-danger">*</span></label>
                                <input type="text" id="lname" name="parent_last_name"
                                       value="<?php echo old('parent_last_name'); ?>" class="form-control form-control-sm"
                                       placeholder="Grandfather's Name" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_phone">Mobile Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="parent_phone" name="parent_phone_mobile"  value="<?php echo old('parent_phone_mobile'); ?>" placeholder="Mobile Phone" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_work">Alternative Phone Number</label>
                                <input type="text" class="form-control form-control-sm" id="parent_work" name="parent_phone_work" value="<?php echo old('parent_phone_work'); ?>" placeholder="Work Phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subcity">Subcity <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="subcity" name="subcity" value="<?php echo old('subcity'); ?>" placeholder="Sub-city" required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="woreda">Woreda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="woreda" name="woreda" value="<?php echo old('woreda'); ?>" placeholder="Woreda" required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="houseno">House Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="houseno" name="house_number" value="<?php echo old('house_number'); ?>" placeholder="House Number" required="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Upload Deposit Slip</label>
                <input type="file" name="slip" class="form-control" accept=".pdf,image/*" />
                <small class="text-danger">Maximum file size: 5MB</small>
            </div>
            <hr class="pt-0 mt--1" />
            <div>
                <div class="form-group">
                    <label><input type="checkbox" name="tac" value="1" required /> I Accept the following Terms and Conditions</label>
                </div>
                <div>
                    <?php
                    $web_terms = get_option('website_terms') ? json_decode(get_option('website_terms')) : '';
                    ?>
                    <ol>
                        <?php if (!empty($web_terms)):
                            foreach ($web_terms as $term):
                            ?>
                        <li>
                          <?php echo $term;?>
                        </li>
                        <?php endforeach;endif;?>
                    </ol>
                </div>
            </div>

            <?php do_action('admin_create_student_form'); ?>
            <div class="">
                <button type="submit" class="btn btn-success btn-block">SUBMIT INFORMATION</button>
            </div>
        </form>
    </div>
</section>
<script>
    $(document).on('click', '#removeRow', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFile', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="title[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <input type="file" name="doc[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRow" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBody").append(html);
        //$('#filesTable').append(html);
    });

    $('#class_list').on('change', function(e) {
        var class_id = $(this).val();
        $('#section_list').html('');
        var url = '<?php echo site_url(); ?>' + 'ajax/class/' + class_id + '/sections';
        $.get(url, function (data, status) {
            $('#section_list').html(data);
        })
    })
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#imgPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#profPic").change(function() {
        readURL(this);
    });
    function parentReadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#ParentImgPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#parentProfPic").change(function() {
        parentReadURL(this);
    });
</script>
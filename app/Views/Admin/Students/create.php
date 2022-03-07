<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">New Student</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <form method="post" class="ajaxForm" loader="true" action="<?php echo current_url(); ?>" data-parsley-validate enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="pb-3">
                            <img src="<?php echo base_url('assets/img/default.jpg'); ?>" id="imgPreview" alt="Image Preview" class="img-thumbnail"/>
                        </div>
                        <div class="form-group">
                            <label for="pic">Profile Image</label>
                            <input type="file" id="profPic" name="profile_pic" accept="image/*" class="form-control form-control-sm"/>
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
                                    <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="dob" required
                                           value="<?php echo old('dob'); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grade">Grade <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm select2" id="class_list" data-toggle="select" name="class" required>
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
                                    <label for="section">Section <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm select2" id="section_list" data-toggle="select" name="section" required>
                                        <option value=""> -- Please select --</option>
                                        <!-- TODO: Fetch sections of the class above -->
                                        <option value="2"> -- TODO - Not Done --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="doa">Date of Admission <span class="text-danger">*</span></label>
                                    <input type="text" name="admission_date" class="form-control form-control-sm datepicker" value="<?php echo old('admission_date', date('m/d/Y')); ?>" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admission_number">Student ID Number <span class="text-danger text-xs">Leave blank to auto-generate</span></label>
                                    <input type="text" name="admission_number" class="form-control form-control-sm" value="<?php echo old('admission_number'); ?>" />
                                </div>
                            </div>
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
                            <label for="pic">Profile Image</label>
                            <input type="file" id="parentProfPic" name="parent_profile_pic" accept="image/*" class="form-control form-control-sm"/>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sname">First Name <span class="text-danger">*</span></label>
                                    <input type="text" id="sname" name="parent_surname" class="form-control form-control-sm"
                                           value="<?php echo old('parent_surname'); ?>" placeholder="First Name" required />
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
                                    <label for="parent_work">Work Phone Number</label>
                                    <input type="text" class="form-control form-control-sm" id="parent_work" name="parent_phone_work" value="<?php echo old('parent_phone_work'); ?>" placeholder="Work Phone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subcity">Subcity <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="subcity" name="subcity" value="<?php echo old('subcity'); ?>" placeholder="Sub-city" required="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="woreda">Woreda <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="woreda" name="woreda" value="<?php echo old('woreda'); ?>" placeholder="Woreda" required="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="houseno">House Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="houseno" name="house_number" value="<?php echo old('houseno'); ?>" placeholder="House Number" required="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nationality">Nationality</label>
                                    <input type="text" class="form-control form-control-sm" id="nationality" name="nationality" value="<?php echo old('nationality'); ?>" placeholder="Nationality">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<!--                <hr class="pt-0 mt--1" />-->
<!--                <h2>Emergency Contact Information</h2>-->
<!--                <div class="row">-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="sname">Surname</label>-->
<!--                            <input type="text" id="sname" name="e_surname" class="form-control form-control-sm"-->
<!--                                   value="--><?php //echo old('e_surname'); ?><!--" placeholder="Surname" />-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="fname">First Name <span class="text-danger">*</span></label>-->
<!--                            <input type="text" id="fname" name="e_first_name" class="form-control form-control-sm"-->
<!--                                   value="--><?php //echo old('e_first_name'); ?><!--" placeholder="First Name" required/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="lname">Last Name <span class="text-danger">*</span></label>-->
<!--                            <input type="text" id="lname" name="e_last_name"-->
<!--                                   value="--><?php //echo old('e_last_name'); ?><!--" class="form-control form-control-sm"-->
<!--                                   placeholder="Last Name" required/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="row">-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group">-->
<!--                            <label for="e_phone">Mobile Phone Number <span class="text-danger">*</span></label>-->
<!--                            <input type="text" class="form-control form-control-sm" id="e_phone" name="e_phone_mobile"  value="--><?php //echo old('e_phone_mobile'); ?><!--" placeholder="Mobile Phone" required="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group">-->
<!--                            <label for="e_work">Work Phone Number</label>-->
<!--                            <input type="text" class="form-control form-control-sm" id="e_work" name="e_phone_work" value="--><?php //echo old('e_phone_work'); ?><!--" placeholder="Work Phone">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="row">-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="subcity">Subcity <span class="text-danger">*</span></label>-->
<!--                            <input type="text" class="form-control form-control-sm" id="subcity" name="e_subcity" value="--><?php //echo old('e_subcity'); ?><!--" placeholder="Sub-city" required="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="woreda">Woreda <span class="text-danger">*</span></label>-->
<!--                            <input type="text" class="form-control form-control-sm" id="woreda" name="e_woreda" value="--><?php //echo old('e_woreda'); ?><!--" placeholder="Woreda" required="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="houseno">House Number <span class="text-danger">*</span></label>-->
<!--                            <input type="text" class="form-control form-control-sm" id="houseno" name="e_house_number" value="--><?php //echo old('e_houseno'); ?><!--" placeholder="House Number" required="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <hr class="pt-0 mt--1" />
                <h2>Additional Information</h2>
                <div class="form-group">
                    <label for="prev_school">Previous School</label>
                    <div class="">
                        <input type="text" class="form-control form-control-sm" id="prev_school" name="previous_school" value="<?php echo old('previous_school'); ?>" placeholder="Previous School">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="child_disabilities">Child Known Disabilities</label>
                            <div class="">
                                <textarea class="form-control" id="child_disabilities" name="child_disabilities" rows="4"><?php echo old('child_disabilities'); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="child_diseases">Child Known Diseases</label>
                            <div class="">
                                <textarea class="form-control" id="child_diseases" name="child_diseases" rows="4"><?php echo old('child_diseases'); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="special_talent">Child Special Talents</label>
                            <div class="">
                                <textarea class="form-control" id="special_talent" name="special_talents" rows="4"><?php echo old('special_talents'); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="future_ambitions">Future Ambitions</label>
                            <div class="">
                                <textarea class="form-control" id="future_ambitions" name="future_ambitions" rows="4"><?php echo old('future_ambitions'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                <div class="form-group">-->
                <!--                    <label for="route">Transportation Route <span class="text-danger">*</span></label>-->
                <!--                    <div class="">-->
                <!--                        <input type="text" class="form-control form-control-sm" id="route" name="transport_route" value="--><?php //echo old('transport_route'); ?><!--" placeholder="Transportation Route" required="">-->
                <!--                    </div>-->
                <!--                </div>-->
                <hr class="pt-0 mt--1" />
                <h2>Admission Results</h2>
                <h4>Test Results</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Amharic</span>
                                </div>
                                <input type="text" class="form-control" name="amharic" value="<?php echo old('amharic'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">English</span>
                                </div>
                                <input type="text" class="form-control" name="english" value="<?php echo old('english'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Math</span>
                                </div>
                                <input type="text" class="form-control" name="math" value="<?php echo old('math'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Social Science</span>
                                </div>
                                <input type="text" class="form-control" name="social_science" value="<?php echo old('social_science'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">General Science</span>
                                </div>
                                <input type="text" class="form-control" name="general_science" value="<?php echo old('general_science'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Biology</span>
                                </div>
                                <input type="text" class="form-control" name="biology" value="<?php echo old('biology'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Physics</span>
                                </div>
                                <input type="text" class="form-control" name="physics" value="<?php echo old('physics'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Chemistry</span>
                                </div>
                                <input type="text" class="form-control" name="chemistry" value="<?php echo old('chemistry'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th>Skill</th>
                            <th>Bad</th>
                            <th>Good</th>
                            <th>Very Good</th>
                            <th>Excellent</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>Speaking</th>
                            <td><input type="radio" name="eng_speaking" <?php echo old('eng_speaking') == 'bad' ? 'checked' : ''; ?> value="bad"></td>
                            <td><input type="radio" name="eng_speaking" <?php echo old('eng_speaking', 'good') == 'good' ? 'checked' : ''; ?> value="good"></td>
                            <td><input type="radio" name="eng_speaking" <?php echo old('eng_speaking') == 'very_good' ? 'checked' : ''; ?> value="very_good"></td>
                            <td><input type="radio" name="eng_speaking" <?php echo old('eng_speaking') == 'excellent' ? 'checked' : ''; ?> value="excellent"></td>
                        </tr>
                        <tr>
                            <th>Listening</th>
                            <td><input type="radio" name="eng_listening" <?php echo old('eng_speaking') == 'bad' ? 'checked' : ''; ?> value="bad"></td>
                            <td><input type="radio" name="eng_listening" <?php echo old('eng_speaking', 'good') == 'good' ? 'checked' : ''; ?> value="good"></td>
                            <td><input type="radio" name="eng_listening" <?php echo old('eng_speaking') == 'very_good' ? 'checked' : ''; ?> value="very_good"></td>
                            <td><input type="radio" name="eng_listening" <?php echo old('eng_speaking') == 'excellent' ? 'checked' : ''; ?> value="excellent"></td>
                        </tr>
                        <tr>
                            <th>Writing</th>
                            <td><input type="radio" name="eng_writing" <?php echo old('eng_speaking') == 'bad' ? 'checked' : ''; ?> value="bad"></td>
                            <td><input type="radio" name="eng_writing" <?php echo old('eng_speaking', 'good') == 'good' ? 'checked' : ''; ?> value="good"></td>
                            <td><input type="radio" name="eng_writing" <?php echo old('eng_speaking') == 'very_good' ? 'checked' : ''; ?> value="very_good"></td>
                            <td><input type="radio" name="eng_writing" <?php echo old('eng_speaking') == 'excellent' ? 'checked' : ''; ?> value="excellent"></td>
                        </tr>
                        <tr>
                            <th>Reading</th>
                            <td><input type="radio" name="eng_reading" <?php echo old('eng_reading') == 'bad' ? 'checked' : ''; ?> value="bad"></td>
                            <td><input type="radio" name="eng_reading" <?php echo old('eng_reading', 'good') == 'good' ? 'checked' : ''; ?> value="good"></td>
                            <td><input type="radio" name="eng_reading" <?php echo old('eng_reading') == 'very_good' ? 'checked' : ''; ?> value="very_good"></td>
                            <td><input type="radio" name="eng_reading" <?php echo old('eng_reading') == 'excellent' ? 'checked' : ''; ?> value="excellent"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <h3>Additional Files</h3>
                    <div class="table-responsive">
                        <table class="table" id="filesTable">
                            <thead>
                            <tr>
                                <th>File</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tableBody">
                            <tr>
                                <td>
                                    <input type="text" name="title[]" class="form-control" />
                                </td>
                                <td>
                                    <input type="file" name="doc[]" class="form-control" />
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-default btn-sm" id="addFile"><i class="fa fa-plus"></i> Add Row</button>
                    <br/><br/>
                </div>

                <?php do_action('admin_create_student_form'); ?>
                <div class="">
                    <button type="submit" class="btn btn-success btn-block">Create Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
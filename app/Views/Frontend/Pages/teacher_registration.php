<?php

?>
<div class="container">
    <?php
    $phones = get_option('teacher_phone') ? json_decode(get_option('teacher_phone')) : '';
    $phones = isset($phones) ? implode(" or ",$phones) : '';
    ?>
    <h1 class="title text-center mt-5">TEACHER REGISTRATION FORM</h1>
    <h5 class=" text-center">FOR ANY ASSISTANCE OR TO REGISTER
        BY PHONE; CALL: <?php echo $phones;?></h5>

    <div class="row">
        <div class="col-md-12">
            <?php if (get_option('teacher_description_file')):
                $docs = json_decode(get_option('teacher_description_file'));
                ?>
            <?php endif;?>
            <div class="image-slider slider-all-controls controls-inside">
                <ul class="slides">
                  <?php if (isset($docs)):
                  foreach ($docs as $doc):
                  ?>
                    <li>
                        <img alt="Kids Expo"
                             src="<?php echo base_url('uploads/files/'.$doc); ?>" class="mission_img">
                    </li>
                    <?php endforeach;endif;?>
                </ul>
            </div>
        </div>
    </div>
</div>
<section id="contact" class="contact section">
    <div class="container">
        <form method="post" class="ajaxForm" loader="true" action="<?php echo current_url(); ?>" enctype="multipart/form-data">
            <input type="hidden" name="form" value="teacher" />
            <div class="row">
                <div class="col-md-3">
                    <div class="pb-3">
                        <img src="<?php echo base_url('assets/img/default.jpg'); ?>" id="imgPreview"
                             alt="Image Preview" class="img-thumbnail"/>
                    </div>
                    <div class="form-group">
                        <label for="pic">Passport Image</label>
                        <input type="file" id="profPic" name="profile_pic" accept="image/*"
                               class="form-control form-control-sm"/>
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
                                <select class="form-control form-control-sm select2" data-toggle="select"
                                        name="gender" required>
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
                                <input type="text" class="form-control form-control-sm datepicker" name="dob"
                                       required
                                       value="<?php echo old('dob'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="e_phone">Mobile Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="phone"
                                       name="phone_mobile" value="<?php echo old('phone_mobile'); ?>"
                                       placeholder="Mobile Phone" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="e_work">Alternative Phone Number</label>
                                <input type="text" class="form-control form-control-sm" id="work"
                                       name="phone_work" value="<?php echo old('phone_work'); ?>"
                                       placeholder="Alternative Phone Number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subcity">Subcity <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="subcity" name="subcity"
                                       value="<?php echo old('subcity'); ?>" placeholder="Sub-city" required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="woreda">Woreda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="woreda" name="woreda"
                                       value="<?php echo old('woreda'); ?>" placeholder="Woreda" required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="houseno">House Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="houseno"
                                       name="house_number" value="<?php echo old('house_number'); ?>"
                                       placeholder="House Number" required="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Years in Experience</label>
                        <input type="number" min="0" class="form-control" name="experience"
                               value="<?php echo old('experience', 0); ?>"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Previous School</label>
                        <input type="text" class="form-control" name="previous_school"
                               value="<?php echo old('previous_school'); ?>"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Subject to Teach</label>
                        <select class="form-control" name="subject_to_teach" required>
                            <option value="">--Please select--</option>
                            <?php
                            $subjects = (new \App\Models\Subjects())->findAll();
                            if($subjects && count($subjects) > 0) {
                                foreach ($subjects as $subject) {
                                    ?>
                                    <option <?php echo old('subject_to_teach') == $subject->id ? 'selected' : ''; ?> value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Education Level</label>
                        <input type="text" class="form-control" name="education_level"
                               value="<?php echo old('previous_school'); ?>"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <h4>Required Documents</h4>
                <div class="table-responsive">
                    <table class="table" id="filesTableTDoc">
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th>File (Pdf only)</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="tableBodyTDoc">
                        <tr>
                            <td>
                                <input type="text" name="teacher_required_file_title[]" class="form-control"/>
                            </td>
                            <td>
                                <input type="file" name="teacher_required_file[]" class="form-control" accept="application/pdf"/>
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: -10px">
                    <button type="button" class="btn btn-default btn-sm" id="addFileTDoc"><i class="fa fa-plus"></i> Add Row</button>
                </div>
                <br/><br/>
            </div>
            <?php do_action('admin_create_teacher_form'); ?>
            <hr/>
            <div class="">
                <button type="submit" class="btn btn-success btn-block">SUBMIT INFORMATION</button>
            </div>
        </form>
    </div>
</section>
<script>
    $(document).on('click', '#removeRowTDoc', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileTDoc', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="teacher_required_file_title[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <input type="file" name="teacher_required_file[]" class="form-control" accept="application/pdf"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowTDoc" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyTDoc").append(html);
        //$('#filesTable').append(html);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imgPreview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#profPic").change(function () {
        readURL(this);
    });
</script>
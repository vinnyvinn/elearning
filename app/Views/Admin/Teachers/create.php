<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">New Teacher</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<style>
  .select2-selection__rendered{
      display: flex !important;
  }
</style>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <form method="post" class="ajaxForm" loader="true" action="<?php echo current_url(); ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-3">
                        <div class="pb-3">
                       <img src="<?php echo base_url('assets/img/default.jpg'); ?>" id="imgPreview"
                                  alt="Image Preview" class="img-thumbnail"/>
                       </div>
                        <div class="form-group">
                            <label for="pic">Profile Image</label>
                            <input type="file" id="profPic" name="profile_pic" accept="image/*"
                                   class="form-control form-control-sm"/>
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
                                    <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="dob"
                                           required
                                           value="<?php echo old('dob'); ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="signature">Signature Image <span class="text-danger">*</span></label>
                                    <input type="file" id="signature" name="signature" accept="image/*"
                                           class="form-control form-control-sm" required/>
                                </div>
                            </div>
                            <div class="col-md-4">
                             <div style="padding-left: 18%;">
                                 <div class="form-group">
                                     <label>Is Director?</label><br/>
                                     <label class="custom-toggle">
                                         <input type="checkbox" name="is_director" class="is_director"
                                                value="1" <?php echo old('is_director', 0) == 1 ? 'checked' : ''; ?> />
                                         <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                               data-label-on="Yes"></span>
                                     </label>
                                 </div>
                             </div>
                            </div>
                            <div class="col-md-4">
                             <div class="director_classes">
                                 <div class="form-group">
                                     <label>Class(es)</label>
                                     <select class="form-control form-control-sm select2" data-toggle="select2" name="director_classes[]" multiple>
                                         <option value=""> -- Select Classes -- </option>
                                         <?php
                                         $classes = (new \App\Models\Classes())->where('session', active_session())->findAll();
                                         if($classes && count($classes) > 0) {
                                             foreach ($classes as $class) {
                                                 ?>
                                                 <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                                 <?php
                                             }
                                         }
                                         ?>
                                     </select>
                                 </div>
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
                                    <label for="e_work">Work Phone Number</label>
                                    <input type="text" class="form-control form-control-sm" id="work"
                                           name="phone_work" value="<?php echo old('phone_work'); ?>"
                                           placeholder="Work Phone">
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
                            <label for="doa">Date of Admission <span class="text-danger">*</span></label>
                            <input type="text" name="admission_date" class="form-control form-control-sm datepicker"
                                   value="<?php echo old('admission_date', date('m/d/Y')); ?>" required/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="admission_number">Teacher ID Number <span class="text-danger text-xs">Leave blank to auto-generate</span></label>
                            <input type="text" name="teacher_number" class="form-control form-control-sm"
                                   value="<?php echo old('teacher_number'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Previous School</label>
                    <input type="text" class="form-control" name="previous_school"
                           value="<?php echo old('previous_school'); ?>"/>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Known Disabilities</label>
                            <textarea class="form-control" name="known_disabilities"
                                      rows="4"><?php echo old('known_disabilities'); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Known Diseases</label>
                            <textarea class="form-control" name="known_diseases"
                                      rows="4"><?php echo old('known_diseases'); ?></textarea>
                        </div>
                    </div>
                </div>

                <hr class="pt-0 mt--1"/>
<!--                <h2>Emergency Contact Information</h2>-->
<!--                <div class="row">-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="sname">First Name</label>-->
<!--                            <input type="text" id="sname" name="e_surname" class="form-control form-control-sm"-->
<!--                                   value="--><?php //echo old('e_surname'); ?><!--" placeholder="Surname"/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="fname">Father's Name <span class="text-danger">*</span></label>-->
<!--                            <input type="text" id="fname" name="e_first_name" class="form-control form-control-sm"-->
<!--                                   value="--><?php //echo old('e_first_name'); ?><!--" placeholder="First Name" required/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="lname">Grandfather's Name <span class="text-danger">*</span></label>-->
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
<!--                            <input type="text" class="form-control form-control-sm" id="e_phone" name="e_phone_mobile"-->
<!--                                   value="--><?php //echo old('e_phone_mobile'); ?><!--" placeholder="Mobile Phone" required="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group">-->
<!--                            <label for="e_work">Work Phone Number</label>-->
<!--                            <input type="text" class="form-control form-control-sm" id="e_work" name="e_phone_work"-->
<!--                                   value="--><?php //echo old('e_phone_work'); ?><!--" placeholder="Work Phone">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="row">-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="subcity">Subcity <span class="text-danger">*</span></label>-->
<!--                            <input type="text" class="form-control form-control-sm" id="subcity" name="e_subcity"-->
<!--                                   value="--><?php //echo old('e_subcity'); ?><!--" placeholder="Sub-city" required="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="woreda">Woreda <span class="text-danger">*</span></label>-->
<!--                            <input type="text" class="form-control form-control-sm" id="woreda" name="e_woreda"-->
<!--                                   value="--><?php //echo old('e_woreda'); ?><!--" placeholder="Woreda" required="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="houseno">House Number <span class="text-danger">*</span></label>-->
<!--                            <input type="text" class="form-control form-control-sm" id="houseno" name="e_house_number"-->
<!--                                   value="--><?php //echo old('e_house_number'); ?><!--" placeholder="House Number" required="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
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
                <?php do_action('admin_create_teacher_form'); ?>
                <div class="">
                    <button type="submit" class="btn btn-success btn-block">Create Teacher</button>
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
    $("#signature").change(function () {
        readURL(this);
    });

    $('.director_classes').hide();
    $('.is_director').on('click',function (){
       if ($('.is_director').is(':checked')){
           $('.director_classes').show();
       }else {
           $('.director_classes').hide();
       }
    })

    //signature
    // $(function() {
    //     var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
    //    // var sig = $('#sig').signature();
    //     $('#clear').click(function(e) {
    //         e.preventDefault();
    //         sig.signature('clear');
    //         $("#signature64").val('');
    //     });
    // });
</script>
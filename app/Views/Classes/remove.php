<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Class Sections</h3>
                    </div>
                    <div class="col text-right">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target=".new_section">New
                            Section
                        </button>
                    </div>
                </div>
                <div class="modal fade new_section" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post"
                                  action="<?php use App\Models\Subjects;

                                  echo site_url(route_to('admin.sections.create')); ?>">
                                <input type="hidden" name="class" value="<?php echo $class->id; ?>"/>
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Class Section</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="sess">Section Name</label>
                                        <input type="text" class="form-control" name="name"
                                               value="<?php echo old('name') ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="advisor">Advisor</label>
                                        <select class="form-control select2" data-toggle="select2" name="advisor">
                                            <option value="">Please select</option>
                                            <?php
                                            $teachers = (new \App\Models\Teachers())->findAll();
                                            if($teachers && count($teachers) > 0) {
                                                foreach ($teachers as $teacher) {
                                                    ?>
                                                    <option value="<?php echo $teacher->id; ?>"><?php echo $teacher->profile->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Maximum # of Students</label>
                                        <input type="number" min="0" class="form-control" name="maximum_students"
                                               value="<?php echo old('maximum_students', 45); ?>" required/>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $sections = $class->sections();
            if ($sections && count($sections) > 0) {
                ?>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Section</th>
                            <th scope="col">Advisor</th>
                            <th scope="col">Capacity</th>
                            <th scope="col"># of Students</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($sections as $section) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td>
                                    <a href="<?php echo site_url(route_to('admin.class.sections.view', $section->id)); ?>"><?php echo $section->name; ?></a>
                                </td>
                                <td>
                                    <?php
                                    if($section->advisor) {
                                        echo $section->advisor->profile->name;
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $section->maximum_students; ?></td>
                                <td><?php echo count($section->students); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item"
                                               href="<?php echo site_url(route_to('admin.class.sections.view', $section->id)); ?>">View</a>
                                            <a class="dropdown-item" href="#!" data-toggle="modal"
                                               data-target=".edit_section<?php echo $section->id; ?>">Edit</a>
                            <?php if (isSuperAdmin()):?>
                                            <a class="dropdown-item send-to-server-click text-danger" href="#!"
                                               url="<?php echo site_url(route_to('admin.sections.delete', $section->id)); ?>"
                                               data="action:delete|id:<?php echo $section->id; ?>" loader="true"
                                               warning-title="Delete Section"
                                               warning-message="Are you sure you want to delete this section and all of its contents?">Delete</a>
                            <?php endif;?>
                                        </div>
                                    </div>
                                    <div class="modal fade edit_section<?php echo $section->id; ?>" tabindex="-1"
                                         role="dialog" aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post"
                                                      action="<?php echo site_url(route_to('admin.sections.edit', $section->id)); ?>">
                                                    <input type="hidden" name="id" value="<?php echo $section->id; ?>"/>
                                                    <input type="hidden" name="class"
                                                           value="<?php echo $class->id; ?>"/>
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Edit
                                                            Section</h6>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="sess">Section Name</label>
                                                            <input type="text" class="form-control" name="name"
                                                                   value="<?php echo old('name', $section->name); ?>"
                                                                   required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="advisor">Advisor</label><br/>
                                                            <select class="form-control select2" data-toggle="select2" name="advisor">
                                                                <option value="">Please select</option>
                                                                <?php
                                                                $teachers = (new \App\Models\Teachers())->findAll();
                                                                if($teachers && count($teachers) > 0) {
                                                                    foreach ($teachers as $teacher) {
                                                                        ?>
                                                                        <option <?php echo ($section->advisor && $section->advisor == $teacher->id) ? 'selected' : ''; ?> value="<?php echo $teacher->id; ?>"><?php echo $teacher->profile->name; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sess">Maximum # of Students</label>
                                                            <input type="number" min="0" class="form-control"
                                                                   name="maximum_students"
                                                                   value="<?php echo old('maximum_students', $section->maximum_students); ?>"
                                                                   required/>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Save</button>
                                                        <button type="button" class="btn btn-link  ml-auto"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <div class="card-body">
                    <div class="alert alert-danger">
                        No sections found for this class
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Class Subjects</h3>
                    </div>
                    <div class="col text-right">
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_subject">Add
                            Subject
                        </button>
                    </div>
                </div>
                <div class="modal fade new_subject" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post"
                                  action="<?php echo site_url(route_to('admin.classes.subjects.add_subject', $class->id)); ?>">
                                <input type="hidden" name="class" value="<?php echo $class->id; ?>"/>
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Class Subject</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="sess">Subject</label>
                                        <select class="form-control select2" data-toggle="select2" name="subject"
                                                required>
                                            <option value=""> -- Please select --</option>
                                            <?php
                                            $subjects = (new Subjects())->findAll();
                                            if ($subjects && count($subjects) > 0) {
                                                foreach ($subjects as $subject) {
                                                    ?>
                                                    <option value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Pass Mark (%)</label>
                                        <input type="number" min="0" max="100" class="form-control" name="pass_mark"
                                               value="<?php echo old('pass_mark', 40); ?>" required/>
                                    </div>
                                    <div style="display: flex">
                                        <div class="form-group">
                                            <label>Subject Optional?</label><br/>
                                            <label class="custom-toggle">
                                                <input type="checkbox" name="optional" class="optional"
                                                       value="1" <?php echo old('optional', 0) == 1 ? 'checked' : ''; ?> />
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                                      data-label-on="Yes"></span>
                                            </label>
                                        </div>
                                        <div class="form-group" style="margin-left: 10%">
                                            <label>Kindergarten?</label><br/>
                                            <label class="custom-toggle">
                                                <input type="checkbox" name="kg" class="optional"
                                                       value="1" <?php echo old('kg', 0) == 1 ? 'checked' : ''; ?> />
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                                      data-label-on="Yes"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="container_add">
                                        <h2>Grading System</h2>
                                        <div style="display: flex">
                                            <span>Grade</span>
                                            <span style="margin-left: 40%">Scale</span>
                                        </div>
                                        <div class="element form-group" id='div_1'>
                                            <input type='text' name="grade[]" placeholder='Enter Grade(A)'  class="form-control txt_1">&nbsp;
                                            <input type='text' name="scale[]" placeholder='Enter Scale(90-100)' class="form-control txt_1">
                                            &nbsp;<i class="fa fa-plus-circle add"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <?php
            $subjects = $class->subjects();
            if ($subjects && count($subjects) > 0) {
                ?>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Pass Mark (%)</th>
                            <th>Optional</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($subjects as $subject) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $subject->name; ?></td>
                                <td><?php echo $subject->pass_mark; ?></td>
                                <td>
                                    <label class="custom-toggle disabled">
                                        <input type="checkbox" disabled
                                               name="optional" <?php echo $subject->optional == 1 ? 'checked' : ''; ?> />
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                              data-label-on="Yes"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item edit_subject" href="#!" data-toggle="modal"
                                               data-target=".edit_subject<?php echo $subject->id;?>" subject_id="<?php echo $subject->id;?>">Edit</a>
                                            <a class="dropdown-item send-to-server-click text-danger" href="#!"
                                               url="<?php echo site_url(route_to('admin.classes.subjects.delete_subject', $subject->id)); ?>"
                                               data="action:delete|id:<?php echo $subject->id; ?>" loader="true"
                                               warning-title="Delete Subject"
                                               warning-message="Are you sure you want to delete this subject and all of its contents for this class?">Delete</a>
                                        </div>
                                    </div>
                                    <div class="modal fade edit_subject<?php echo $subject->id; ?>" tabindex="-1"
                                         role="dialog" aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post"
                                                      action="<?php echo site_url(route_to('admin.classes.subjects.edit_subject', $subject->id)); ?>">
                                                    <?php $grading = (new \App\Models\ClassSubjects())->find($subject->id)->grading;
                                                    $scales = '';
                                                    if ($grading){
                                                        $scales = json_decode($grading);
                                                    }?>
                                                    <input type="hidden" name="id" value="<?php echo $subject->id; ?>"/>
                                                    <?php $sub_id = $subject->id?>
                                                    <input type="hidden" name="class"
                                                           value="<?php echo $class->id; ?>"/>
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Update
                                                            Subject</h6>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Subject</label><br/>
                                                            <select class="form-control select2" data-toggle="select2"
                                                                    name="subject" required>
                                                                <option value=""> -- Please select --</option>
                                                                <?php
                                                                $subjects = (new Subjects())->findAll();
                                                                if ($subjects && count($subjects) > 0) {
                                                                    foreach ($subjects as $subjct) {
                                                                        ?>
                                                                        <option <?php echo $subject->subject == $subjct->id ? 'selected' : ''; ?>
                                                                            value="<?php echo $subjct->id; ?>"><?php echo $subjct->name; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sess">Pass Mark (%)</label>
                                                            <input type="number" min="0" max="100" class="form-control"
                                                                   name="pass_mark"
                                                                   value="<?php echo old('pass_mark', $subject->pass_mark); ?>"
                                                                   required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Subject Optional?</label><br/>
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" name="optional"
                                                                       value="1" <?php echo old('optional', $subject->optional) == 1 ? 'checked' : ''; ?> />
                                                                <span class="custom-toggle-slider rounded-circle"
                                                                      data-label-off="No" data-label-on="Yes"> </span>
                                                            </label>
                                                        </div>

                                                        <?php if( ($scales) && is_array($scales)):?>
                                                            <div class="container_edit">
                                                                <!--                                                            $("#div_" + nextindex).append("<input type='text' placeholder='Enter Grade(B)' name='grade[]' class='form-control txt_'"+ nextindex +"'>&nbsp;<input type='text' name='scale[]' placeholder='Enter Scale(80-90)'  class='form-control txt_"+ nextindex +"'>&nbsp;<span id='remove_" + nextindex + "' class='remove'><i class='fa fa-trash'></i></span>");-->
                                                                <h2>Grading System</h2>
                                                                <div style="display: flex">
                                                                    <span>Grade</span>
                                                                    <span style="margin-left: 40%">Scale</span>
                                                                </div>
                                                                <?php foreach ($scales as $key => $val):?>
                                                                    <div class="element form-groupc co" style="display: flex" id="divv_<?php echo $key;?>">
                                                                        <input type="text" value="divv_<?php echo $key;?>">
                                                                        <input type='text' name="grade[]" placeholder='Enter Grade(A)'  value="<?php echo $val->grade;?>" class="form-control txt_<?php echo $key;?>">&nbsp;
                                                                        <input type='text' name="scale[]" placeholder='Enter Scale(90-100)' value="<?php echo $val->scale;?>" class="form-control txt_<?php echo $key;?>">
                                                                        &nbsp;<span style="text-align: right" id="remove_<?php echo $key ?>" class="remove"><i class='fa fa-trash'></i><?php echo $key;?></span>
                                                                    </div>
                                                                <?php endforeach;?>
                                                                &nbsp;<i class="fa fa-plus-circle add"></i>

                                                            </div>
                                                        <?php else:?>
                                                            <div class="container_add">
                                                                <h2>Grading System</h2>
                                                                <div style="display: flex">
                                                                    <span>Grade</span>
                                                                    <span style="margin-left: 40%">Scale</span>
                                                                </div>
                                                                <div class="element form-group" id='div_1'>
                                                                    <input type='text' name="grade[]" placeholder='Enter Grade(A)'  class="form-control txt_1">&nbsp;
                                                                    <input type='text' name="scale[]" placeholder='Enter Scale(90-100)' class="form-control txt_1">
                                                                    &nbsp;<i class="fa fa-plus-circle add"></i>
                                                                </div>
                                                            </div>
                                                        <?php endif;?>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Save</button>
                                                        <button type="button" class="btn btn-link  ml-auto"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <div class="card-body">
                    <div class="alert alert-danger">
                        No subjects found for this class
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                Class Events
            </div>
            <?php
            if($class->events && is_array($class->events) && count($class->events) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Event</th>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($class->events as $event) {
                            ?>
                            <tr>
                                <td><?php echo $event->name; ?></td>
                                <td><?php echo $event->starting_date; ?></td>
                                <td><?php echo $event->ending_date; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <div class="card-body">
                    <div class="alert alert-info">
                        No event for this class
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="col-12">

    </div>
</div>

<style>
    .container_add{
        /*width: 50%;*/
        /*margin: 0 auto;*/
        display: none;
    }
    #div_1{
        display: flex;
    }
    .element{
        margin-bottom: 10px;
    }

    .add,.remove{
        /*border: 1px solid black;*/
        /*padding: 2px 10px;*/
        margin-top: 2%;
        font-size: 26px;
    }

    .add:hover,.remove:hover{
        cursor: pointer;
    }
</style>

<script>
    $(document).ready(function(){

        $('.optional').on('click',function (){
            if ($(this).is(':checked')){
                $('.container_add').show();
            }else {
                $('.container_add').hide();
            }
        })
        // Add new element
        $(".add").click(function(){

            // Finding total number of elements added
            var total_element = $(".element").length;
            console.log(total_element)
            // last <div> with element class id
            var lastid = $(".element:last").attr("id");
            var split_id = lastid.split("_");
            var nextindex = Number(split_id[1]) + 1;

            var max = 5;
            // Check total number elements
            // if(total_element < max ){
            // Adding new div container after last occurance of element class
            $(".element:last").after("<div class='element' id='div_"+ nextindex +"' style='display: flex'></div>");

            // Adding element to <div>
            $("#div_" + nextindex).append("<input type='text' placeholder='Enter Grade(B)' name='grade[]' class='form-control txt_'"+ nextindex +"'>&nbsp;<input type='text' name='scale[]' placeholder='Enter Scale(80-90)'  class='form-control txt_"+ nextindex +"'>&nbsp;<span id='remove_" + nextindex + "' class='remove'><i class='fa fa-trash'></i></span>");

            // <input type='text' placeholder='Enter Grade(A)' id='txt_1' class="form-control">&nbsp;
            //     <input type='text' placeholder='Enter Scale(90-100)' id='txt_2' class="form-control">
            //         &nbsp;<i class="fa fa-plus-circle add"></i>
            //  }

        });


        // Remove element
        $('.container_add').on('click','.remove',function(){

            var id = this.id;
            var split_id = id.split("_");
            var deleteindex = split_id[1];

            // Remove <div> with id
            $("#div_" + deleteindex).remove();
        });
        $(document).on('click', '.remove', function() {
            $(this).parent().parent('.element').remove();
        })

        $('.container_edit').on('click','.remove',function(){

            var id = this.id;
            console.log('id== '+id)
            var split_id = id.split("_");
            var deleteindex = split_id[1];

            console.log('del index--> '+deleteindex)
            // Remove <div> with id
            console.log($("#divv_" + deleteindex).remove())
            $("#divv_" + deleteindex).remove();
        });
    });
</script>
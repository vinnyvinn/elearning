<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Student Attendance</h6><br/>
                    <small class="text-white">Student Attendance</small>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('student_attendance_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$option = isset($attend->option_type) ? $attend->option_type : '';
?>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <form method="post" action="<?php echo site_url(route_to('admin.attendance.saveStudent')); ?>" class="ajaxForm" loader="true">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control select2" data-toggle="select2" name="class" required id="class"
                                    onchange="getSections()">
                                <option value=""> -- Class --</option>
                                <?php
                                $classes = getSession()->classes->findAll();
                                if ($classes && count($classes) > 0) {
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control select2" data-toggle="select2" name="section" required id="section">
                                <option value=""> -- Section --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input class="form-control form-control-sm" data-provide="datepicker" data-date-end-date="0d" type="text" id="date" name="date" value="<?php echo date('m/d/Y'); ?>"
                                   required />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="filter" class="btn btn-sm btn-primary btn-block"
                                onclick="getStudents()">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 offset-2">
                        <div class="row formData">
                            <div class="col-md-3">
                                <div class="form-group">
                                <label>Events/Holidays? <?php echo $option?></label><br/>
                                <label class="custom-toggle">
                                    <input type="checkbox" name="events"
                                           value="1" <?php echo $option =='events' ? 'checked' : ''; ?> id="events"/>
                                    <span class="custom-toggle-slider rounded-circle"
                                          data-label-off="No" data-label-on="Yes"></span>
                                </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                             <div class="form-group">
                                 <label>Weekend ?</label><br>
                                 <label class="custom-toggle">
                                     <input type="checkbox" name="weekend"
                                            value="1" <?php echo $option =='weekend' ? 'checked' : ''; ?> id="weekend"/>
                                     <span class="custom-toggle-slider rounded-circle"
                                           data-label-off="No" data-label-on="Yes"></span>
                                 </label>
                             </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Exams ?</label><br>
                                    <label class="custom-toggle">
                                        <input type="checkbox" name="exams"
                                               value="1" <?php echo $option == 'exams' ? 'checked' : ''; ?> id="exams" />
                                        <span class="custom-toggle-slider rounded-circle"
                                              data-label-off="No" data-label-on="Yes"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="button" class="btn btn-success event">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="ajaxContent"></div>
        </form>
    </div>
</div>
<script>
    $(function (){
        if ($('#events').is(':checked') || $('#weekend').is(':checked') || $('#exams').is(':checked')){
            $('#filter').hide();
        }
    })
    $('#date').on('change',function (){
        var data = {
            url: "<?php echo site_url(route_to('admin.attendance.students.get_ajax_attendance')) ?>",
            data: "date=" + $(this).val(),
            loader: true
        };
        ajaxRequest(data, function (data) {
                let output = JSON.parse(data)
               if (output !==null){
                   $('#filter').hide();
                   console.log(output.option_type)
                   if (output.option_type === 'exams'){
                       $('#exams').prop("checked",true);
                       $('#weekend').prop("checked",false);
                       $('#events').prop("checked",false);

                       $('#exams').attr('disabled',false) ;
                       $('#weekend').attr('disabled',true) ;
                       $('#events').attr('disabled',true) ;
                   }
                   else if (output.option_type === 'weekend'){
                       $('#exams').prop("checked",false);
                       $('#weekend').prop("checked",true);
                       $('#events').prop("checked",false);

                       $('#exams').attr('disabled',true) ;
                       $('#events').attr('disabled',true) ;
                       $('#weekend').attr('disabled',false) ;
                   }
                   else if (output.option_type === 'events'){
                       $('#exams').prop("checked",false);
                       $('#weekend').prop("checked",false);
                       $('#events').prop("checked",true);

                       $('#exams').attr('disabled',true) ;
                       $('#weekend').attr('disabled',true) ;
                       $('#events').attr('disabled',false) ;
                   }
               }else {
                   $('#exams').prop("checked",false);
                   $('#weekend').prop("checked",false);
                   $('#events').prop("checked",false);

                   $('#exams').attr('disabled',false) ;
                   $('#weekend').attr('disabled',false) ;
                   $('#events').attr('disabled',false) ;
                   $('#filter').show();
               }
        });
    })

    $(function (){
        if ($('#exams').is(':checked')){
            $('#events').attr('disabled',true) ;
            $('#weekend').attr('disabled',true) ;
        }
        if ($('#events').is(':checked')){
            $('#exams').attr('disabled',true) ;
            $('#weekend').attr('disabled',true) ;
        }
        if ($('#weekend').is(':checked')){
            $('#events').attr('disabled',true) ;
            $('#exams').attr('disabled',true) ;
        }
    })
    var getSections = function () {
        var classId = $('#class').val();
        if (classId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#section').html(data);
            });
        }
    };

    var getStudents = function () {
        var classId = $('#class').val();
        var sectionId = $('#section').val();
        var date = $('#date').val();
        if (classId == '' || sectionId == '' || date == '') {
            toast('Error', 'Please make sure all filter fields are selected', 'error');
        } else {
            var data = {
                url: "<?php echo site_url(route_to('admin.attendance.students.get_ajax')) ?>",
                data: "class=" + classId + "&section=" + sectionId + "&date=" + date,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#ajaxContent').html(data);
            });
        }
    }

    $('#events').on('click',function (){
        if ($(this).is(':checked')){
         $('#exams').attr('disabled',true);
         $('#weekend').attr('disabled',true);
            $('#filter').hide();
        }else {
            $('#exams').attr('disabled',false) ;
            $('#weekend').attr('disabled',false) ;
            $('#filter').show();
        }
    })

    $('#exams').on('click',function (){
        if ($(this).is(':checked')){
            $('#filter').hide();
            $('#events').attr('disabled',true) ;
            $('#weekend').attr('disabled',true) ;
        }else {
            $('#events').attr('disabled',false) ;
            $('#weekend').attr('disabled',false) ;
            $('#filter').show();
        }
    })
    $('#weekend').on('click',function (){
        if ($(this).is(':checked')){
            $('#filter').hide();
            $('#events').attr('disabled',true) ;
            $('#exams').attr('disabled',true) ;
        }else {
            $('#events').attr('disabled',false) ;
            $('#exams').attr('disabled',false) ;
            $('#filter').show();
        }
    })

    $('.event').on('click',function (e){
        e.preventDefault();
        var formElements = new Array();
        $(".formData :input").each(function(){
            formElements.push($(this));
        });

        let option = 'normal';
        if ($('#events').is(':checked')){
            option = 'events';
        }
        if ($('#weekend').is(':checked')){
            option = 'weekend';
        }
        if ($('#exams').is(':checked')){
            option = 'exams';
        }
        let date = $('#date').val();

        var data = {
            url: "<?php echo site_url(route_to('admin.attendance.students.post_ajax')) ?>",
            data: "option=" + option+"&date="+date,
            loader: true
        };
        ajaxRequest(data, function (data) {
            console.log(data)
        });
    })
</script>
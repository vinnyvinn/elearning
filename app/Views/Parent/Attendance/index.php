<?php
$students = $parent->studentsCurrent;

$attendance = (new \App\Models\Attendance())->where('student',$students[0]->id)->orderBy('date_created','DESC')->get()->getRow();
if ($attendance){
    $year = date('Y',$attendance->timestamp);
    $month = date('m',$attendance->timestamp);
}
$student_id = $students[0]->id;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Student Attendance</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('parent_quick_action_buttons', $parent); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card" style="margin-bottom: 5%">
        <div class="card-header">
            <h4 class="card-title">Student Attendance</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="tab-content px-1 pt-1">

                     <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control select2 year" data-toggle="select2" name="year">
                                                    <?php
                                                    for ($i = date('Y') - 5; $i <= date('Y'); $i++) {
                                                        ?>
                                                        <option <?php echo date('Y') == $i ? 'selected' : ''; ?>
                                                                value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control select2 month" data-toggle="select2">
                                                    <option value=""> -- Month --</option>
                                                    <option <?php echo date('m') == '01' ? 'selected' : ''; ?> value='01'>January
                                                    </option>
                                                    <option <?php echo date('m') == '02' ? 'selected' : ''; ?> value='02'>February
                                                    </option>
                                                    <option <?php echo date('m') == '03' ? 'selected' : ''; ?> value='03'>March</option>
                                                    <option <?php echo date('m') == '04' ? 'selected' : ''; ?> value='04'>April</option>
                                                    <option <?php echo date('m') == '05' ? 'selected' : ''; ?> value='05'>May</option>
                                                    <option <?php echo date('m') == '06' ? 'selected' : ''; ?> value='06'>June</option>
                                                    <option <?php echo date('m') == '07' ? 'selected' : ''; ?> value='07'>July</option>
                                                    <option <?php echo date('m') == '08' ? 'selected' : ''; ?> value='08'>August
                                                    </option>
                                                    <option <?php echo date('m') == '09' ? 'selected' : ''; ?> value='09'>September
                                                    </option>
                                                    <option <?php echo date('m') == '10' ? 'selected' : ''; ?> value='10'>October
                                                    </option>
                                                    <option <?php echo date('m') == '11' ? 'selected' : ''; ?> value='11'>November
                                                    </option>
                                                    <option <?php echo date('m') == '12' ? 'selected' : ''; ?> value='12'>December
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="student" class="std" <?php echo $student_id;?>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-sm btn-success btn-block"
                                                        onclick="getStudentAttendance()">
                                                    <i class="fa fa-filter"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ajaxAttendance"></div>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $('.month').on('change',function (){
        $('.month').val($(this).val());
    })
    $('.year').on('change',function (){
        $('.year').val($(this).val());
    })

    var attendance = "<?php $attendance;?>";
    if(attendance !==null){
        var year ="<?php echo $year;?>";
        var month ="<?php echo $month;?>";
        var student = "<?php echo $student_id;?>";
        attendanceDetails(year,month,student);
    }

    function attendanceDetails(year,month,student){
        $('.year').val(year)
        $('.month').val(month)
        setTimeout(()=>{
            var d = {
                url: "<?php echo site_url(route_to('parent.attendance.students.ajax')); ?>",
                data: 'year=' + year + '&month=' + month + '&student=' + student,
                loader: true
            }
            ajaxRequest(d, function (data) {
                $('.ajaxAttendance').html(data);
            });
        },1000)
    }

    var getStudentAttendance = function () {
        var month = $('.month').val();
        var year = $('.year').val();
        var student = "<?php echo $student_id;?>";
        if (month != '' && year != '' && student != '') {
            var d = {
                url: "<?php echo site_url(route_to('parent.attendance.students.ajax')); ?>",
                data: 'year=' + year + '&month=' + month + '&student=' + student,
                loader: true
            }
            ajaxRequest(d, function (data) {
                $('.ajaxAttendance').html(data);
            });
        } else {
            toast("Error", "Please select the Year, Month and Student", 'error');
        }
    }
</script>
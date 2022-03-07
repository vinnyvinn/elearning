<?php
$sections = (new \App\Models\Sections())->where('advisor',$teacher->id)->findAll();
$students = array();
foreach ($sections as $section){
    foreach ($section->students as $std){
        array_push($students,$std);
    }
}

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
                    do_action('parent_quick_action_buttons', $teacher); ?>
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
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control select2 student" data-toggle="select2">
                        <?php
                        if ($students && count($students) > 0) {
                            foreach ($students as $student) {
                                ?>
                                <option value="<?php echo $student->id; ?>"><?php echo $student->profile->name; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                </div>
               <div class="col-md-3">
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
               <div class="col-md-3">
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

               <div class="col-md-3">
                   <div class="form-group">
                       <button type="button" class="btn btn-sm btn-success btn-block"
                               onclick="getStudentAttendance()">
                           <i class="fa fa-filter"></i> Filter
                       </button>
                   </div>
               </div>
           </div>
                  <div class="ajaxAttendance"></div>
                 </div>
            </div>
        </div>
<script>

    setTimeout(()=>{
        getStudentAttendance();
    },1000)

 var getStudentAttendance = function () {
     var month = $('.month').val();
     var year = $('.year').val();
     var student = $('.student').val();
     if (month != '' && year != '' && student != '') {
         var d = {
             url: "<?php echo site_url(route_to('teacher.attendance.students.ajax')); ?>",
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
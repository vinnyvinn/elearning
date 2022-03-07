<?php
$classes = getSession()->classes->findAll();

$students = '';
for ($i=0; $i<count($classes);$i++){
    $students = $classes[$i]->students;
    if (is_array($classes[$i]->students) && count($classes[$i]->students)>0)
        break;
}
$exams = $students[0]->getExams();
$exam_ = $exams[0]->id;
$student = $students[0];
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Exam Schedule</h6>
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
            <h4 class="card-title">Exam Schedule  </h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <select name="class" id="class_id" class="form-control select2" required>
                                <?php foreach ($classes as $class):?>
                                    <option value="<?php echo isset($class->students[0]->id) ? $class->students[0]->id : '';?>" <?php if (isset($class->students[0]->id) && $students[0]->id == $class->students[0]->id):?>selected<?php endif;?>><?php echo $class->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select name="exam" id="exam_id" class="form-control select2" required>
                                <?php foreach ($exams as $exam):?>
                                    <option value="<?php echo $exam->id;?>"><?php echo $exam->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                <div class="ajaxContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var student = "<?php echo $student->id;?>";
    var exam = "<?php echo $exam_;?>";

       setTimeout(()=>{

              $('.exam').val(exam);
              getSchedule(exam,student);
              },1000)


          $('#exam_id').on('change',function (){
              exam = $(this).val();
            getSchedule(exam,student);
          })

        $('#class_id').on('change',function (){
            student = $(this).val();
            console.log('stude '+student)
            getSchedule(exam,student)
        })

    var getSchedule = function (exam,student) {
            if (exam !== '' && student !== '') {
                var d = {
                    url: "<?php echo site_url(route_to('teacher.exams.schedule-ajax')) ?>",
                    data: "student=" + student + "&exam=" + exam,
                    loader: true
                }
                ajaxRequest(d, function (data) {
                    $('.ajaxContent').html(data);
                })
            } else {
                toast("Error", "No student found in the selected class", 'error');
            }


    }
</script>

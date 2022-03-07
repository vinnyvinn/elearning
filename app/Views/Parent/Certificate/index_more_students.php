<?php
$students = $parent->studentsCurrent;
$stud = $parent->studentsCurrent[0];
$sess = active_session();
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                <h6 class="h2 text-white d-inline-block mb-0">Yearly Certificate <span class="student_name"></span> </h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('parent_certificate_quick_action_buttons', $parent); ?>
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('parent.student.certificate.print', $stud->id)); ?>">Print</a>
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('parent.student.certificate.download-cert', $stud->id)); ?>">Download Pdf</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills nav-pill-bordered">
                    <?php
                    $active = $students[0];
                    foreach ($students as $student):?>
                        <li class="nav-item">
                            <a href="#" class="walla nav-link <?php if ($stud->id == $student->id):?>active<?php endif;?>" id="base-pill<?php echo $student->id;?>" data-toggle="pill" href="#pill<?php echo $student->id;?>" aria-expanded="true" student-id="<?php echo $student->id;?>"><?php echo $student->profile->name;
                                echo '<br>';
                                echo $student->class->name;
                                echo '<br>';
                                echo $student->admission_number;
                                ?></a>
                        </li>
                    <?php endforeach;?>
                </ul>
                <div class="tab-content px-1 pt-1">
                    <?php
                    foreach ($students as $student):?>
                        <div role="tabpanel" class="tab-pane <?php if ($stud->id == $student->id):?>active<?php endif;?>" id="pill<?php echo $student->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $student->id;?>">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control form-control-sm year" id="year">
                                                    <option>-- Select Session --</option>
                                                    <?php
                                                    $sessions = (new \App\Models\Sessions())->orderBy('id', 'DESC')->findAll();
                                                    $active = active_session();
                                                    if(is_array($sessions) && count($sessions) > 0) {
                                                        $n = 0;
                                                        foreach ($sessions as $session) {
                                                            $n++;
                                                            ?>
                                                            <option <?php echo $active == $session->id ? 'selected' : ''; ?> value="<?php echo $session->id; ?>"><?php echo $session->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="student" class="std">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getRes()">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>

            </div>
          <div class="">
            <div class="ajaxContent"></div>
        </div>
        </div>
    </div>
</div>
<script>
    var session = "<?php echo $sess;?>";
    var student = "<?php echo $stud->id?>";

    $('.year').on('change',function (){
        session = $(this).val();
    })
    $('.walla').on('click',function (){
        student = $(this).attr('student-id');
        session = $('.year').val();
       getCertificate(session,student);
    })

    $('.year').val(session);
    $('.std').val(student);
    setTimeout(()=>{
        getCertificate(session,student);
    },500)
    function getRes(){
        getCertificate(session,student);
    }

    function getCertificate(session,student) {
        var d = {
            url: "<?php echo site_url(route_to('parent.student.report-card')); ?>",
            loader: true,
            data: "year="+session+"&student="+student
        };
        ajaxRequest(d, function (data) {
            console.log(data)
            $('.ajaxContent').html(data);
        })
    }
</script>
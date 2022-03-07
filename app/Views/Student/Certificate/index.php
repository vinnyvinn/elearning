<?php
$sess = active_session();
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Yearly Certificate (<?php echo $student->profile->name;?>)</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('parent_certificate_quick_action_buttons', $student); ?>
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('student.certificate.print', $student->id)); ?>">Print</a>
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('student.certificate.download-cert', $student->id)); ?>">Download Pdf</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="container">
        <div class="card">
            <div class="card-body ">
                <div>
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
                <div class="">
                    <div id="ajaxContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var session = "<?php echo $sess;?>";
    var student = "<?php echo $student->id?>";

    $('.year').on('change',function (){
      session = $(this).val();
    })

    $('.year').val(session);
    $('.std').val(student);
    setTimeout(()=>{
        getCertificate(session,student);
    },1000);
    function getRes(){
        getCertificate(session,student);
    }
    function getCertificate(session,student) {
        var d = {
            url: "<?php echo site_url(route_to('student.report-card')); ?>",
            loader: true,
            data: "year="+session+"&student="+student
        };
        ajaxRequest(d, function (data) {
            $('#ajaxContent').html(data);
        })
    }
</script>